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
use App\Service\ItemsService;
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


    /**
     * validate idno
     *
     * @param Request $request
     * @param ItemsService $itemsService
     * @param string $idno
     * @param string $purpose
     *
     * @return JsonResponse
     *
     * @Route("/validate/idno/{idno<[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}>?}/{purpose?}", name="validate", methods={"GET"})
     */
    public function validate(Request $request, ItemsService $itemsService, $idno, $purpose = 'register'): ?JsonResponse
    {
        $item = $itemsService->check(
            $request->get('p_idno') ?? $idno,
            null,
            $purpose
        );

        // status
        $status = $item === null
            ? 412
            : 200;

        // return
        return (new JsonResponse())
            ->setStatusCode($status)
            ->setData(
                [
                    'valid' => $item !== null
                ]
            );
    }
}
