<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Person | onCreated
 */
class PersonCreated
{
    /**
     * postCreate | send mail for email verification
     *
     * @param Person $person
     * @param LifecycleEventArgs $event
     * @return void
     */
    public function postCreate(Person $person, LifecycleEventArgs $event): void
    {
/*
        // mail
        $this->mailService->infoMail(
            [
                'subject' => 'Information - Ihr ID-No.com Produkt wurde genutzt!',
                'recipientEmail' => $person->getEmail(),
                'recipientName' => $person->getFullName(),
                'person' => $person,
                'now' => new \DateTime(),
            ],
            'itemScanned'
        );
*/

    }

}