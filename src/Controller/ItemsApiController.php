<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
use App\Form\Type\ItemsAddType;
use App\Form\Type\ItemsEditType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * items api controller
 * @Route("/api/items", name="app_api_items_")
 */
class ItemsApiController extends AbstractApiController
{
    /**
     * @var string entityClassName
     */
    public static $entityClassName = Items::class;

    /**
     * @var string entityFormAddType
     */
    public static $entityFormAddType = ItemsAddType::class;

    /**
     * @var string entityFormEditType
     */
    public static $entityFormEditType = ItemsEditType::class;


    /**
     * create
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        return $this->json(
            [],
            403
        );
    }


    /**
     * delete
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/delete/{id}", name="delete", methods={"POST","DELETE"})
     */
    public function delete(int $id, Request $request): JsonResponse
    {
        return $this->json(
            [],
            403
        );
    }


    /**
     * get entity operatons
     *
     * @param iterable $objects
     * @return array
     */
    public function mapOperations($objects): iterable
    {
        $items = [];

        // iterate
        foreach ($objects as $item) {

            // voter check | read
            $this->denyAccessUnlessGranted('read', $item);

            // set operations
            $item->setOperations(
                [
                    'edit' => [
                        'icon' => $this->settings['buttons']['edit'],
                        'uri' => $this->generateUrl(
                            'app_items_edit',
                            [
                                'id' => $item->getId(),
                            ]
                        )
                    ],
                    'delete' => [
                        'icon' => $this->settings['buttons']['delete'],
                        'uri' => $this->generateUrl(
                            'app_items_delete',
                            [
                                'id' => $item->getId(),
                            ]
                        )
                    ],

                ]
            );

            // add
            $items[] = $item;
        }

        // return
        return $items;
    }
}
