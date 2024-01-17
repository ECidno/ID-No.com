<?php

namespace App\Command;

use App\Entity\Nutzer;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountReminderCommand extends Command
{
    protected static $defaultName = 'app:account:reminder';
    protected static $defaultDescription = 'Send account reminder e-mails to users';

    private $entityManager;
    private $mailService;
    private $translator;

    public function __construct(EntityManagerInterface $em, MailService $mailService, TranslatorInterface $translator)
    {
        $this->entityManager = $em;
        $this->mailService = $mailService;
        $this->translator = $translator;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Dry run');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repository = $this->entityManager->getRepository(Nutzer::class);

        $users = $repository->findForReminderMail();
        if($input->getOption('dry-run')) {
            $io->note('Dry mode enabled');
            $io->note('users found: '. count($users));
            return 1;
        } else {
            $count = 1;
            $io->note('users found: '. count($users));
            foreach($users as $user) {
                $io->note($user->getId().' '.$user->getPersons()->first()->getLastChangeDatum()->format('d.m.Y'));

                $this->mailService->infoMail(
                    [
                        'subject' => $this->translator->trans('mail.accountReminder.subject'),
                        'recipientEmail' => 'mtakke@mp-group.net', //$user->getEmail(),
                        'recipientName' => $user->getFullName(),
                        'nutzer' => $user,
                        'person' => $user->getPersons()->first(),
                    ],
                    'accountReminder'
                );
                $user->setInformationSendDatum(new \DateTime('now'));
                $this->entityManager->persist($user);
                
                $count--;
                if ($count <= 0) {
                    break;
                }
            }
            $this->entityManager->flush();
        }

        return 0;
    }
}
