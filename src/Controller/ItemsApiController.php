<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Main\Items;
use App\Entity\Nutzer\Nutzer;
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
     * create
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $now = new \DateTime();
        $object = new static::$entityClassName();
        $formType = static::$entityFormAddType;

        // form
        $form = $this->createForm($formType, $object);
        $form->handleRequest($request);

        // form valid
        if ($form->isSubmitted() && $form->isValid()) {

            // voter
            $this->denyAccessUnlessGranted('create', $object);

            // validate item
            $idno = $object->getIdNo();
            $item = $this->itemsService->check(
                $idno,
                'itemError',
                'register'
            );

            // validation failed
            if($item === null) {
                return $this->json(
                    [
                        'message' => $this->translator->trans(
                            $this->getTranslateKey('action.create.error')
                        ),
                        'errors' => [],
                    ],
                    400
                );
            }

            // update item
            $item
                ->setNoStatus('registriert')
                ->setNutzerId($object->getNutzerId())
                ->setPersonId($object->getPersonId())
                ->setAnbringung($object->getAnbringung())
                ->setRegistriertDatum($now);

            // persist
            $this->emDefault->flush($item);

            // message
            $message = $this->translator->trans(
                $this->getTranslateKey('action.create.success')
            );

        // form invalid
        } else if (!$form->isValid()) {
            $errors = $this->collectFormErrors($form);
            $message = $this->translator->trans(
                $this->getTranslateKey('action.create.error')
            );

            // Return status code 400 for validation errors: https://stackoverflow.com/a/3290198
            return $this->json(
                [
                    'message' => $message,
                    'errors' => $errors,
                ],
                400
            );
        }

        // return | page
        return $this->json(
            [
                'id' => $object->getId(),
                'message' => $message,
            ]
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
        $object = $this->emDefault
            ->getRepository(static::$entityClassName)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $object);

        // csrf
        if (
            $this->isCsrfTokenValid(
                'delete'.$object->getId(),
                $request->request->get('_token')
            )
        ) {

            // update item
            $object
                ->setNoStatus('aktiviert')
                ->setNutzerId(0)
                ->setPersonId(0)
                ->setAnbringung(null)
                ->setRegistriertDatum(null);

            // persist
            $this->emDefault->flush($object);

            // message
            $message = $this->translator->trans(
                $this->getTranslateKey('action.delete.success')
            );

         // invalid request
        } else {
            $message = $this->translator->trans(
                $this->getTranslateKey('action.delete.error')
            );

            // return
            return $this->json(
                [
                    'errors' => $message,
                ],
                412
            );
        }

        // return
        return $this->json(
            [
                'message' => $message,
            ]
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
     * @Route("/validate/idno/{idno<[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}>?}/{purpose}", name="validate", methods={"GET"})
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
            ? 400
            : 200;

        // return
        return (new JsonResponse())
            ->setStatusCode($status)
            ->setData(
                [
                    'valid' => $item !== null,
                    'error' => $this->translator->trans(
                        'item.idno.validate.error'
                    )
                ]
            );
    }
}
