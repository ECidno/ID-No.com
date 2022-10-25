<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 * /*********************************************************************/

use App\Entity\Items;
use App\Repository\ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param ItemsRepository $repository
     *
     * @return Response
     *
     * @Route("/notfallpass/{idno?}", name="app_item_pass", methods={"GET", "POST"})
     */
    public function pass($idno = null, Request $request, ItemsRepository $repository): Response
    {
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // get item
        $item = $repository->findOneByIdNo($idno);

        // item?
        if($item !== null) {

            // variables
            $variables = [
                'item' => $item,
                'idno' => strtoupper($idno),
            ];

        // redirect to index
        } else {
            return $this->redirectToRoute('app_standard_index');
        }

        // return
        return $this->renderAndRespond($variables);
    }



}