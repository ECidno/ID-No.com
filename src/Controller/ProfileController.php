<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use App\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * profile controller
 */
class ProfileController extends AbstractController
{
    /**
     * index action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/meinidno", name="app_profile_index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /**
         * @var Nutzer
         */
        $user = $this->getUser();

        // objects
        $persons = $user->getPersons();
        $person = $persons->first() ?? [];

        // vars
        $variables = [
            'user' => $user,
            'person' => $person,
        ];

        // return
        return $this->renderAndRespond($variables);
    }
}
