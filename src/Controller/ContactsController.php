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
     * index action
     *
     * @param Request $request
     * @return Response
     *
     * @Route("/", name="index", methods={"GET", "POST"})
     */
    public function index(Request $request): Response
    {
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // vars
        $variables = [
            'user' => $this->getUser(),
        ];

        // return
        return $this->renderAndRespond($variables);
    }


    /**
     * new action
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

        // voter check
        $this->denyAccessUnlessGranted('edit', $person);
/*
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $user,
            ]);

        // check person
        if($person === null) {
            return (new JsonResponse())
                ->setStatusCode(412)
                ->setData(
                    [
                        'severity' => 9,
                        'message' => $this->translator->trans(
                            'action.err.not_allowed'
                        )
                    ]
                );
        }
*/
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
     * edit action
     *
     * @param int $personId
     * @param int $id
     * @param Request $request
     * @return Response
     *
     * @Route("/contact/edit/{personId}/{id}", name="edit", methods={"GET"})
     */
    public function edit(int $personId, int $id, Request $request): Response
    {
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $this->getUser(),
            ]);

        // voter check
        $this->denyAccessUnlessGranted('edit', $person);


        /*
        // user authenticated
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $person = $this->emNutzer
            ->getRepository(Person::class)
            ->findOneBy([
                'id' => $personId,
                'nutzer' => $user,
            ]);

        // check person
        if($person === null) {
            return (new JsonResponse())
                ->setStatusCode(412)
                ->setData(
                    [
                        'severity' => 9,
                        'message' => $this->translator->trans(
                            'action.err.not_allowed'
                        )
                    ]
                );
        }
*/
        // contact
        $contact = $this->emNutzer
            ->getRepository(Contact::class)
            ->find($id);

        // voter check
        $this->denyAccessUnlessGranted('edit', $contact);
/*
        // return error if not allowed
        if(!$person->getContacts()->contains($contact)) {
            return (new JsonResponse())
                ->setStatusCode(412)
                ->setData(
                    [
                        'severity' => 9,
                        'message' => $this->translator->trans(
                            'action.err.not_allowed'
                        )
                    ]
                );
        }
*/
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
}
