<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Nutzer;
use App\Entity\Nutzer\NutzerAuth;
use App\Service\MailService;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param ManagerRegistry $registry
     * @param MailService $mailService
     * @param TranslatorInterface $translator
     * @return void
     */
    public function postCreate(
        Nutzer $nutzer,
        LifecycleEventArgs $event,
        ManagerRegistry $registry,
        MailService $mailService,
        TranslatorInterface $translator
    ): void
    {
        // get auth code object
        $nutzerAuth = $registry
            ->getManager('nutzer')
            ->getRepository(NutzerAuth::class)
            ->findOneByNutzer($nutzer);

        // mail for email verification
        $mailService->infoMail(
            [
                'subject' => $translator->trans('mail.mailVerification.subject'),
                'recipientEmail' => $nutzer->getEmail(),
                'recipientName' => $nutzer->getFullName(),
                'nutzer' => $nutzer,
                'nutzerAuth' => $nutzerAuth,
            ],
            'mailVerification'
        );
    }
}