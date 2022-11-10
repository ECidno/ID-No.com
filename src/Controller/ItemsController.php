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
use App\Form\Type\ItemsAddType;
use App\Form\Type\ItemsEditType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * items controller
 *
 * @Route("/", name="app_items_")
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
     * @Route("/notfallpass/{idno?}", name="pass", methods={"GET", "POST"})
     */
    public function pass($idno = null, Request $request): Response
    {
        $idno = strtoupper($request->get('p_idno') ?? $idno);

        // get item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->findOneByIdNo($idno);

        // not found
        if($item === null) {
            return $this->redirectToRoute('app_standard_index');
        }

        // switch item statis (noStatus)
        switch ($item->getNoStatus()) {
            case 'deaktiviert':
                return $this->redirectToRoute('app_standard_index');
                break;

            // ready for activation
            case 'aktiviert':
                return $this->redirectToRoute('app_standard_index');
                break;

            // active
            case 'registriert':

                // proceed
                $nutzerId = $item->getNutzerId();
                $personId = $item->getPersonId();

                // user
                $nutzer = $this->emNutzer
                    ->getRepository(Nutzer::class)
                    ->findOneById($nutzerId);

                // user pass active (sichtbar)
                if($nutzer->getSichtbar() === false) {
                    return $this->redirectToRoute('app_standard_index');
                }

                // variables
                $variables = [
                    'idno' => $item,
                    'nutzer' => $nutzer,
                    'person' => $this->emNutzer
                        ->getRepository(Person::class)
                        ->findOneById($personId),
                ];

                // return
                return $this->renderAndRespond($variables);
                break;

            // redirect to index - to be sure
            default:
                return $this->redirectToRoute('app_standard_index');
                break;
        }
    }


    /**
     * new
     *
     * @param int $personId
     * @param Request $request
     * @return Response
     *
     * @Route("/items/new/{personId}", name="new", methods={"GET"})
     */
    public function new(int $personId, Request $request): Response
    {
        $user = $this->getUser();
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $this->getUser(),
            ]);

        // voter
        $this->denyAccessUnlessGranted('edit', $person);

        // new item
        $item = new Items();
        $item
            ->setNutzerId($user->getId())
            ->setPersonId($person->getId());

        // form
        $form = $this->formFactory->createBuilder(
            ItemsAddType::class,
            $item,
            [
                'action' => $this->generateUrl('app_api_items_create'),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'form' => $form->createView()
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }


    /**
     * edit
     *
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/items/edit/{id}", name="edit", methods={"GET"})
     */
    public function edit(int $id, Request $request): Response
    {
        // item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('edit', $item);

        // form
        $form = $this->formFactory->createBuilder(
            ItemsEditType::class,
            $item,
            [
                'action' => $this->generateUrl(
                    'app_api_items_update',
                     [
                        'id' => $id
                    ]
                ),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'item' => $item,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }


    /**
     * delete
     *
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/items/delete/{id}", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request): Response
    {
        // item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $item);

        // form
        $form = $this
            ->createFormBuilder($item)
            ->setAction(
                $this->generateUrl(
                    'app_api_items_delete',
                    [
                        'id' => $id
                    ]
                )
            )
            ->getForm();

        // vars
        $variables = [
            'item' => $item,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }
}