<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use App\Form\Type\PersonType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * person controller
 *
 * @Route("/person", name="app_person_")
 */
class PersonController extends AbstractController
{

    /**
     * new
     *
     * @param int $personId
     * @param Request $request
     * @return Response
     *
     * @Route("/new", name="new", methods={"GET"})
     */
    public function new(Request $request): Response
    {
        $person = new Person();
        $person->setNutzer($this->getUser());

        // form
        $form = $this->formFactory->createBuilder(
            PersonType::class,
            $person,
            [
                'action' => $this->generateUrl('app_api_person_create'),
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
     * @Route("/edit/{id}", name="edit", methods={"GET"})
     */
    public function edit(int $id, Request $request): Response
    {
        // person
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('edit', $person);

        // form
        $form = $this->formFactory->createBuilder(
            PersonType::class,
            $person,
            [
                'action' => $this->generateUrl(
                    'app_api_person_update',
                     [
                        'id' => $id
                    ]
                ),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'person' => $person,
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
     * @Route("/delete/{id}", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request): Response
    {
        // person
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $person);

        // form
        $form = $this
            ->createFormBuilder($person)
            ->setAction(
                $this->generateUrl(
                    'app_api_person_delete',
                    [
                        'id' => $id
                    ]
                )
            )
            ->getForm();

        // vars
        $variables = [
            'person' => $person,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }
}
