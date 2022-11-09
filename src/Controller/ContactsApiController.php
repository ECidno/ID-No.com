<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Contact;
use App\Form\Type\ContactType;
use Symfony\Component\Routing\Annotation\Route;

/**
 * contacts api controller
 * @Route("/api/contacts", name="app_api_contacts_")
 */
class ContactsApiController extends AbstractApiController
{
    /**
     * @var string entityClassName
     */
    public static $entityClassName = Contact::class;

    /**
     * @var string entityFormAddType
     */
    public static $entityFormAddType = ContactType::class;

    /**
     * @var string entityFormEditType
     */
    public static $entityFormEditType = ContactType::class;


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
                            'app_contacts_edit',
                            [
                                'id' => $item->getId(),
                            ]
                        )
                    ],
                    'delete' => [
                        'icon' => $this->settings['buttons']['delete'],
                        'uri' => $this->generateUrl(
                            'app_contacts_delete',
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
