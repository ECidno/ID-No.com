<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

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
}
