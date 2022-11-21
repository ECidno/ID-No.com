<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Nutzer | Created
 */
class NutzerCreated
{
    /**
     * postCreate | send mail for email verification
     *
     * @param Nutzer $nutzer
     * @param LifecycleEventArgs $event
     * @return void
     */
    public function postCreate(Nutzer $nutzer, LifecycleEventArgs $event): void
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