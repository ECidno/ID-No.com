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
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Nutzer | Created
 */
class NutzerAuthListener
{
    private $registry;
    private $mailService;
    private $translator;

    public function __construct(ManagerRegistry $registry, MailService $mailService, TranslatorInterface $translator)
    {
        $this->registry = $registry;
        $this->mailService = $mailService;
        $this->translator = $translator;
    }

    /**
     * postPersist | send mail for email verification
     *
     * @param NutzerAuth $nutzerAuth
     * @return void
     */
    public function postPersist(NutzerAuth $nutzerAuth): void
    {
        // get nutzer
        $nutzer = $nutzerAuth->getNutzer();

        // mail for email verification
        $this->mailService->infoMail(
            [
                'subject' => $this->translator->trans('mail.mailVerification.subject'),
                'recipientEmail' => $nutzer->getEmail(),
                'recipientName' => $nutzer->getFullName(),
                'nutzer' => $nutzer,
                'nutzerAuth' => $nutzerAuth,
            ],
            'mailVerification'
        );
    }

    /**
     * preUpdate | send mailfor email verification again
     *             only when auth changed
     *
     * @param Nutzer $nutzer
     * @param PreUpdateEventArgs $event
     * @return void
     */
    public function preUpdate(NutzerAuth $nutzerAuth, PreUpdateEventArgs $event): void
    {
        
        if ($event->hasChangedField('auth')) {
            
            // get nutzer
            $nutzer = $nutzerAuth->getNutzer();

            // mail for email verification
            $this->mailService->infoMail(
                [
                    'subject' => $this->translator->trans('mail.mailVerification.subject'),
                    'recipientEmail' => $nutzer->getEmail(),
                    'recipientName' => $nutzer->getFullName(),
                    'nutzer' => $nutzer,
                    'nutzerAuth' => $nutzerAuth,
                ],
                'mailVerification'
            );
        }
    }
}