<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    * @return Response
    *
    * @Route("/account/edit", name="app_account_edit", methods={"GET"})
    */
    public function edit(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // vars
        $variables = [
            'user' => $this->getUser(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }

    /**
     * Check User Authentification with auth_code from Mail
     *
     * @param string $auth_code
     * @return void
     * 
     * @Route("/auth/{auth_code}", name="app_account_authenticate")
     */
    public function auth(string $auth_code)
    {
        # TODO: check user is not logedin

        // check auth_code
        $nutzerAuth = $this->emNutzer
            ->getRepository(NutzerAuth::class)
            ->findOneByAuth($auth_code);

        if (!empty($nutzerAuth)) {

            if ($nutzerAuth->getStatus() != 'neu') {
                // auth_code nicht gültig bzw. bereits benutzt
            }

            // check if the auth_code is older than 2 hours
            $now = time();
            $diff = $now - $nutzerAuth->getTime();
            if($diff >= 7200) {
                // auth_code nicht mehr gültig
                // create new and send new mail
            }

            $nutzer = $this->emNutzer
                ->getRepository(Nutzer::class)
                ->findOneyById($nutzerAuth->getNutzer());

            $nutzerAuth->setStatus('ok');
            $nutzer
                ->setStatus('ok')
                ->setAktiviertDatum(date("Y-m-d H:i:s"));

            $this->emNutzer->persist($nutzerAuth);
            $this->emNutzer->persist($nutzer);
            $this->emNutzer->flush();


        } else {
            // falscher auth_code
        }
    }
}
