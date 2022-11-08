<?php
namespace App\Controller;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Contact;
use App\Form\Type\ContactType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
}
