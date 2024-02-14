<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use App\Entity\Items;
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
            'persons' => $persons,
            'layout' => $this->ajax
                ? 'ajax'
                : 'default',
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * list action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/profiles", name="app_profile_list", methods={"GET"})
     */
    public function list(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        /**
         * @var Nutzer
         */
        $user = $this->getUser();

        // get items per person
        $persons = [];
        foreach ($user->getPersons() as $person) {
            $itemCount = $this->emDefault
                ->getRepository(Items::class)
                ->count(
                    [
                        'person' => $person
                    ]
                );

            // set item count and add to array
            $persons[] = $person->setItemCount($itemCount);
        }

        // vars
        $variables = [
            'person_list' => $persons,
            'layout' => $this->ajax === true
                ? 'ajax'
                : 'default',
        ];

        // return
        return $this->renderAndRespond($variables);
    }
}
