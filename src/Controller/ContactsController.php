<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Contact;
use App\Entity\Nutzer\Person;
use App\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * contacts controller
 *
 * @Route("/contacts", name="app_contacts_")
 */
class ContactsController extends AbstractController
{
    /**
     * new
     *
     * @param int $personId
     * @param Request $request
     * @return Response
     *
     * @Route("/contact/new/{personId}", name="new", methods={"GET"})
     */
    public function new(int $personId, Request $request): Response
    {
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $this->getUser(),
            ]);

        // voter
        $this->denyAccessUnlessGranted('edit', $person);

        // new contact
        $contact = new Contact();
        $contact->setPerson($person);

        // form
        $form = $this->formFactory->createBuilder(
            ContactType::class,
            $contact,
            [
                'action' => $this->generateUrl('app_api_contacts_create'),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'contact' => $contact,
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
     * @Route("/contact/edit/{id}", name="edit", methods={"GET"})
     */
    public function edit(int $id, Request $request): Response
    {
        // contact
        $contact = $this->emNutzer
            ->getRepository(Contact::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('edit', $contact);

        // form
        $form = $this->formFactory->createBuilder(
            ContactType::class,
            $contact,
            [
                'action' => $this->generateUrl(
                    'app_api_contacts_update',
                     [
                        'id' => $id
                    ]
                ),
            ]
        )
        ->getForm();

        // vars
        $variables = [
            'contact' => $contact,
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
     * @Route("/contact/delete/{id}", name="delete", methods={"GET"})
     */
    public function delete(int $id, Request $request): Response
    {
        // contact
        $contact = $this->emNutzer
            ->getRepository(Contact::class)
            ->find($id);

        // voter
        $this->denyAccessUnlessGranted('delete', $contact);

        // form
        $form = $this
            ->createFormBuilder($contact)
            ->setAction(
                $this->generateUrl(
                    'app_api_contacts_delete',
                    [
                        'id' => $id
                    ]
                )
            )
            ->getForm();

        // vars
        $variables = [
            'contact' => $contact,
            'form' => $form->createView(),
        ];

        // return
        return $this->renderAndRespond(
            $variables,
            true
        );
    }
}
