<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use App\Entity\Main\Items;
use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\Person;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * items controller
 *
 * Route("/item", name="app_item_")
 */
class ItemsController extends AbstractController
{
    // entity
    public static $entityClassName = Items::class;


    /**
     * pass action
     *
     * @param string $idno
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/notfallpass/{idno?}", name="app_item_pass", methods={"GET", "POST"})
     */
    public function pass($idno = null, Request $request): Response
    {
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // get item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->findOneByIdNo($idno);

        // item?
        if($item !== null) {
            $nutzerId = $item->getNutzerId();
            $personId = $item->getPersonId();

            // variables
            $variables = [
                'idno' => $item,
                'nutzer' => $this->emNutzer
                    ->getRepository(Nutzer::class)
                    ->findOneById($nutzerId),
                'person' => $this->emNutzer
                    ->getRepository(Person::class)
                    ->findOneById($personId),
            ];

        // redirect to index
        } else {
            return $this->redirectToRoute('app_standard_index');
        }

        // return
        return $this->renderAndRespond($variables);
    }



}