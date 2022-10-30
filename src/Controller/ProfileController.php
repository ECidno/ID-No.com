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

/**
 * Profile controller
 */
class ProfileController extends AbstractController
{
    /**
     * index action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/meinidno", name="app_meinidno", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
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
    * profile action
    *
    * @param Request $request
    * @return Response
    *
    * @Route("/profil", name="app_profile", methods={"GET", "POST"})
    */
   public function profile(Request $request): Response
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
