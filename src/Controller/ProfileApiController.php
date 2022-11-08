<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
        $user = $this->getUser();
        $object = $this->em
            ->getRepository(static::$entityClassName)
            ->find($id);
        $enable = ((bool) $request->get('sichtbar')) ?? null;

        // check, update
        if(
            $user->getId() === $id &&
            $enable !== null &&
            $this->isCsrfTokenValid(
                'pass_enable',
                $request->request->get('token')
            )
        ) {
            $object->setSichtbar($enable);
            $this->em->flush();

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


}
