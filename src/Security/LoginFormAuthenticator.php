<?php
namespace App\Security;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 /*********************************************************************/

use App\Entity\Nutzer\Nutzer;
use App\Repository\NutzerRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;


/**
 * Login form authenticator
 */
class LoginFormAuthenticator extends AbstractLoginFormAuthenticator implements AuthenticationEntryPointInterface
{
    /**
     * @var ManagerRegistry $registry
     */
    private $registry;

    /**
     * @var NutzerRepository $userRepository
     */
    private $userRepository;

    /**
     * @var UrlGeneratorInterface $urlGenerator
     */
    private $urlGenerator;

    /**
     * @var CsrfTokenManagerInterface $csrfTokenManager
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordHasherInterface $passwordEncoder
     */
    private $passwordEncoder;

    /**
     * @var ParameterBagInterface $params
     */
    private $params;


    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry,
#        NutzerRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordHasherInterface $passwordEncoder,
        ParameterBagInterface $params
    ) {
        $this->registry = $registry;
 #       $this->userRepository = $userRepository;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->params = $params;
    }

    /**
     * authenticate
     *
     * @param Request $request
     * @return array
     */
    public function authenticate(Request $request): Passport
    {
        $credentials = [
            'username' => $request->request->get('_username'),
            'password' => $request->request->get('_password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $username = $credentials['username'];
        $password = $credentials['password'];

        $em = $this->registry->getManager('nutzer');

        // credentials?
        if (
            empty($username) ||
            empty($password)
        ) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Empty credentials!');
        }

        // csrf
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        // get user
#        $user = $this->userRepository->findOneBy(['email' => $username]);
        $user = $em
            ->getRepository(Nutzer::class)
            ->findOneBy(['email' => $username]);

        // user found?
        if (!$user || !$user->isAllowedToLogin()) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found!');
        }

        // old pass check | return badge
        if(
            md5(
                substr(
                    htmlentities(
                        $username,
                        ENT_QUOTES
                    ),
                    2,
                    2
                )
                .htmlentities(
                    $password,
                    ENT_QUOTES
                )
            ) === $user->getPasswort()
        ) {
            /*
             * @TODO set pass to new encoder here and save
             */

            // reset login errors
            $user
                ->setLoginFehler(0)
                ->setLastLogin(new \DateTime());
            $em->persist($user);
            $em->flush();

            // return
            return new SelfValidatingPassport(new UserBadge($username), []);
/*
            return new Passport(
                new UserBadge(
                    $username,
                    function($userIdentifier) {
                        $user = $this->userRepository->findOneBy(['email' => $userIdentifier]);

                        // user found?
                        if (!$user || !$user->isAllowedToLogin()) {
                            // fail authentication with a custom error
                            throw new CustomUserMessageAuthenticationException('Username could not be found!');
                        }

                        // return
                        return $user;
                    }
                ),

                new CustomCredentials(
                    function($credentials, Nutzer $user) {
                        $oldPassCheck = md5(
                            substr(
                                htmlentities(
                                    $user->getUserIdentifier(),
                                    ENT_QUOTES
                                ),
                                2,
                                2
                            )
                            .htmlentities(
                                $credentials,
                                ENT_QUOTES
                            )
                        ) === $user->getPasswort();

                        return $oldPassCheck;
                    },
                    $password
                )
            );
*/
        }

        // pass check | return badge
        if(
            $this->passwordEncoder->isPasswordValid(
                $user,
                $credentials['password']
            ) === true
        ) {
            // reset login errors
            $user
                ->setLoginFehler(0)
                ->setLastLogin(new \DateTime());
            $em->persist($user);
            $em->flush();

            // return
            return new SelfValidatingPassport(new UserBadge($username), []);
        }

        // increase user's login errors
        $loginFehler = $user->getLoginFehler();
        $user->setLoginFehler($loginFehler++);
        $em->persist($user);
        $em->flush();

        // fail authentication with a custom error
        throw new CustomUserMessageAuthenticationException('Die Kombination aus Benutzernamen und Passwort ist nicht im System hinterlegt.');
    }


    /**
     * Authentication successful
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     *
     * @return ?Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('app_meinidno')
        );
    }


    /**
     * Get login url
     *
     * @param Request $request
     * @return string
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}