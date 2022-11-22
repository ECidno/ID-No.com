<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * profile api controller
 *
 * @Route("/api/profile", name="app_api_profile_")
 */
class ProfileApiController extends AbstractApiController
{
    /**
     * @var string entityClassName
     */
    public static $entityClassName = Nutzer::class;


    /**
     * pass_enable
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/pass_enable/{id}", name="pass_enable", methods={"POST"})
     */
    public function pass_enable(int $id, Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $object = $this->getUser();
        $enable = ((bool) $request->get('sichtbar')) ?? null;

        // check, update
        if(
            $enable !== null &&
            $this->isCsrfTokenValid(
                'pass_enable',
                $request->request->get('token')
            )
        ) {
            $object->setSichtbar($enable);
            $this->emNutzer->flush();

            // message, severity
            $message = $this->translator->trans(
                $enable === true
                    ? 'profile.actions.pass_enable.success'
                    : 'profile.actions.pass_disable.success'
            );
            $severity = $enable === true
                ? 0
                : 1;

        // fail
        } else {
            $message = $this->translator->trans(
                'profile.actions.pass_enable_disable.error'
            );
            $severity = 9;
        }

        // return
        return (new JsonResponse())
            ->setStatusCode(200)
            ->setData(
                [
                    'id' => $object->getId(),
                    'severity' => $severity,
                    'message' => $message,
                ]
            );
    }


    /**
     * email auth request
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/emailauthrequest", name="email_auth_request", methods={"GET"})
     */
    public function emailauthrequest(Request $request): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $email = $user->getEmail();

        // hash for email verification
        do {
            $auth = ByteString::fromRandom(40)->toString();
        } while(
            $this->emNutzer
                ->getRepository(NutzerAuth::class)
                ->findOneByAuth($auth) !== null
        );

        // get auth code object
        $nutzerAuth = $this->emNutzer
            ->getRepository(NutzerAuth::class)
            ->findOneByNutzer($user);

        // proceed
        if($nutzerAuth) {
            $nutzerAuth
                ->setAuth($auth)
                ->setTime(time())
                ->setNutzer($user);

            $this->emNutzer->persist($nutzerAuth);
            $this->emNutzer->flush();

            // mail for email verification
            $this->mailService->infoMail(
                [
                    'subject' => $this->translator->trans('mail.mailVerification.subject'),
                    'recipientEmail' => $email,
                    'recipientName' => $user->getFullName(),
                    'nutzer' => $user,
                    'nutzerAuth' => $nutzerAuth,
                ],
                'mailVerification'
            );

            // return
            return (new JsonResponse())
                ->setStatusCode(200)
                ->setData(
                    [
                        'severity' => 0,
                        'message' => $this->translator->trans(
                            'profile.actions.emailauthrequest.success',
                            [
                                '%email%' => $email
                            ]
                        )
                    ]
                );

        // auth | error
        } else {
            return (new JsonResponse())
                ->setStatusCode(412)
                ->setData(
                    [
                        'severity' => 9,
                        'message' => $this->translator->trans(
                            'profile.actions.emailauthrequest.error',
                            [
                                '%email%' => $email
                            ]
                        )
                    ]
                );
        }
    }


    /**
     * validate email
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param string $email
     *
     * @return JsonResponse
     *
     * @Route("/validate/email/{email?}", name="validate", methods={"GET"})
     */
    public function validate(Request $request, ValidatorInterface $validator, $email): JsonResponse
    {
        $user = $this->emNutzer
            ->getRepository(Nutzer::class)
            ->findOneByEmail($email);

        // valid?
        $valid =
            $user === null &&
            !empty($email) &&
            $validator
                ->validate(
                    $email,
                    new Assert\Email()
                )
                ->count() === 0;

        // status
        $status = $valid
            ? 200
            : 412;

        // return
        return (new JsonResponse())
            ->setStatusCode($status)
            ->setData(
                [
                    'valid' => $valid,
                    'errors' => ''
                ]
            );
    }
}
