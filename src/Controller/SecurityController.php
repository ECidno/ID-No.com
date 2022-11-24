<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use App\Entity\Nutzer\Person;
use App\Form\Type\CredentialsChangeType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Security controller
 */
class SecurityController extends AbstractController
{
    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     *
     * @Route("/login", name="app_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // vars
        $variables = [
            'last_username' => $lastUsername,
            'error' => $error,
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void
    { }


    /**
    * edit action
    *
    * @param Request $request
    * @param UserPasswordHasherInterface $passwordEncoder
    *
    * @return Response
    *
    * @Route("/account/edit", name="app_account_edit")
    */
    public function edit(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /**
         * @var Nutzer
         */
        $nutzer = $this->getUser();

        $form = $this->createForm(CredentialsChangeType::class, $nutzer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $nutzer->setPasswort(
                $passwordEncoder->hashpassword(
                    $nutzer,
                    $nutzer->getPlainPasswort()
                )
            );

            $person = $this->emNutzer
                            ->getRepository(Person::class)
                            ->findOneByNutzer($nutzer);

            $person->setEmail($nutzer->getEmail());
            

            $this->emNutzer->persist($nutzer);
            $this->emNutzer->persist($person);
            $this->emNutzer->flush();

            // redirect to profile
            return $this->redirectToRoute('app_profile_index');
        }

        // vars
        $variables = [
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * Check User Authentification with auth_code from Mail
     *
     * @param ?string $auth_code
     * @return void
     *
     * @Route("/auth/{auth_code?}", name="app_account_authenticate")
     */
    public function auth(string $auth_code = null, Request $request): Response
    {
        // check auth_code
        $nutzerAuth = $this->emNutzer
            ->getRepository(NutzerAuth::class)
            ->findOneByAuth($auth_code);

        // enter auth code manually
        if (empty($auth_code)) {

            $form = $this->createFormBuilder()
                ->add('auth_code', TextType::class, [
                    'attr' => [
                        'minlength' => 40,
                        'maxlength' => 40,
                    ]
                ])
                ->add('send', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            // use automatic validation logic
            if ($form->isSubmitted() && $form->isValid()) {
                $auth_code = $form->get('auth_code')->getData();
                return $this->redirectToRoute('app_account_authenticate', ['auth_code' => $auth_code]);
            }

            $variables = [
                'form' => $form->createView(),
                'error' => null
            ];
    
            // return
            return $this->renderAndRespond($variables);
        }

        if (!empty($nutzerAuth)) {

            $nutzer = $this->emNutzer
                ->getRepository(Nutzer::class)
                ->findOneById($nutzerAuth->getNutzer());

            // check if the auth_code is older than 2 hours
            $now = time();
            $diff = $now - $nutzerAuth->getTime();
            if($diff >= 7200) {
                $error = 'code.expired';
            }

            // auth_code already used
            if ($nutzerAuth->getStatus() != 'neu') {
                $error = 'code.used';
            }

            // verify nutzer
            if (empty($error)) {
                $nutzerAuth->setStatus('ok');
                $nutzer
                    ->setStatus('ok')
                    ->setAktiviertDatum(new DateTime());

                $this->emNutzer->persist($nutzerAuth);
                $this->emNutzer->persist($nutzer);
                $this->emNutzer->flush();

                $this->addFlash(
                    'loginInfo',
                    $this->translator->trans('You are verified')
                );

                // return
                return $this->redirectToRoute('app_login');
            }

        // invalid auth_code
        } else {
            $error = 'code.invalid';
        }

        $variables = [
            'form' => null,
            'error' => $error,
        ];

        // return
        return $this->renderAndRespond($variables);
    }
}
