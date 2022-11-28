<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use App\Entity\Nutzer\Person;
use App\Entity\Nutzer\PwdVergessen;
use App\Form\Type\CredentialsChangeType;
use App\Validator\EmailExists;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\String\ByteString;
use Symfony\Component\Translation\TranslatableMessage;

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

    /**
     * Reset Password
     *
     * @param string $email
     * @return void
     *
     * @Route("/resetPassword/{code?}", name="app_account_resetPasswort")
     */
    public function resetPassword(string $code = null, Request $request, UserPasswordHasherInterface $passwordEncoder)
    {
        if (empty($code)) {
            $form = $this->createFormBuilder()
                ->add('email', EmailType::class, [
                   'label' => new TranslatableMessage('person.email'),
                   'attr' => [
                       'maxlength' => 100,
                       'autocomplete' => 'off',
                   ],
                   'constraints' => new EmailExists(),
                   'required' => true,
                ])
                ->add('send', SubmitType::class, [
                    'label' => new TranslatableMessage('resetPassword.submit')
                ])
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $email = strtolower($form->get('email')->getData());

                $person = $this->emNutzer
                            ->getRepository(Person::class)
                            ->findOneByEmail($email);

                // code for password reset
                do {
                    $code = ByteString::fromRandom(15)->toString();
                } while(
                    $this->emNutzer
                        ->getRepository(PwdVergessen::class)
                        ->findOneByCode($code) !== null
                );

                $pwdForgot = new PwdVergessen();
                $pwdForgot
                    ->setEmail($email)
                    ->setCode($code);

                $this->emNutzer->persist($pwdForgot);
                $this->emNutzer->flush();

                    // mail
                $this->mailService->infoMail(
                    [
                        'subject' => 'Passworthilfe',
                        'recipientEmail' => $person->getEmail(),
                        'recipientName' => $person->getFullName(),
                        'person' => $person,
                        'code' => $code
                    ],
                    'resetPassword'
                );

                $this->addFlash(
                    'loginInfo',
                    $this->translator->trans('Reset Password Mail was sended')
                );

            }

            $variables = [
                'form' => $form->createView(),
            ];
    
            // return
            return $this->renderAndRespond($variables);

        } else {

            $email = $this->emNutzer
                        ->getRepository(PwdVergessen::class)
                        ->findOneByCode($code);

            if(empty($email)) {
                $this->addFlash(
                    'loginInfo',
                    $this->translator->trans('Invalid Code')
                );

                return $this->redirectToRoute('app_login');
            }

            $nutzer = $this->emNutzer
                        ->getRepository(Nutzer::class)
                        ->findOneByEmail($email->getEmail());

            $form = $this->createFormBuilder($nutzer)
                ->add('plainPasswort', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'attr' => [
                        'maxlength' => 100,
                        'autocomplete' => 'off',
                    ],
                    'first_options' => [
                        'label' => new TranslatableMessage('credentials.newPassword')
                    ],
                    'second_options' => [
                        'label' => new TranslatableMessage('credentials.newRepeatPassword')
                    ],
                    'required' => true
                ])
                ->add('save', SubmitType::class)
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $nutzer->setPasswort(
                    $passwordEncoder->hashpassword(
                        $nutzer,
                        $nutzer->getPlainPasswort()
                    )
                );

                $this->emNutzer->persist($nutzer);
                $this->emNutzer->flush();

                $this->addFlash(
                    'loginInfo',
                    $this->translator->trans('New Password was set')
                );

                return $this->redirectToRoute('app_login');
            }

            $variables = [
                'form' => $form->createView(),
            ];

            return $this->render('security/newPassword.html.twig', $variables);
        }
    }

    /**
    * delete action
    *
    * @param Request $request
    *
    * @return Response
    *
    * @Route("/account/delete", name="app_account_delete")
    */
    public function delete(Request $request, SessionInterface $session, TokenStorageInterface $tokenStorage): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createFormBuilder()
                    ->add('password', PasswordType::class, [
                        'mapped' => false,
                        'label' => new TranslatableMessage('registration.passwort'),
                        'constraints' => new UserPassword(),
                        'required' => true
                ])
                ->add('accountDelete', SubmitType::class)
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**
             * @var Nutzer
             */
            $nutzer = $this->getUser();

            $items = $this->emDefault
                        ->getRepository(Items::class)
                        ->findByNutzerId($nutzer->getId());

            foreach($items as $item) {
                $item
                    ->setNoStatus('aktiviert')
                    ->setNutzerId(0)
                    ->setPersonId(0)
                    ->setAnbringung('');

                $this->emDefault->persist($item);
            }

            foreach($nutzer->getPersons() as $person) {
                foreach($person->getContacts() as $contact) {
                    $this->emNutzer->remove($contact);
                }
                $this->emNutzer->remove($person);
            }
            $this->emNutzer->remove($nutzer);


            $this->emDefault->flush();
            $this->emNutzer->flush();

            
            $tokenStorage->setToken(null);
            $session->invalidate();

            // logout
            return $this->redirectToRoute('app_logout');
        }

        // vars
        $variables = [
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }
}
