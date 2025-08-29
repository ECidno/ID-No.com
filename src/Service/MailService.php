<?php
namespace App\Service;

/***********************************************************************
 *
 * (c) 2022 Frank Krüger <fkrueger@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use App\Entity\LogEntry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;

/**
 * Service for mail
 */
class MailService
{
    /**
     * const DEBUG_RECIPIENT
     */
    const DEBUG_RECIPIENT = 'fkrueger@mp-group.net';

    /**
     * @var MailerInterface $mailer
     */
    private $mailer;

    /**
     * @var EntitiyManager $em
     */
    protected $em;

    /**
     * Constructor
     *
     * @param MailerInterface $mailer
     */
    public function __construct(
        MailerInterface $mailer,
        ManagerRegistry $registry
    ) {
        $this->mailer = $mailer;
        $this->em = $registry->getManager('default');
    }


    /**
     * send debug mail
     *
     * @param array $data
     *
     * @return void
     */
    public function debugMail(array $data): void
    {
        $subject = '[ID-NO DEBUG] '.($data['subject'] ?? 'General error');

        // iterate data
        $rows = [];
        $htmlRows = [];
        foreach ($data as $key => $value) {
            $rows[] = $key.': '.$value;
            $htmlRows[] = '<tr><td>'.$key.'</td><td>'.$value.'</td></tr>';
        }

        // mail
        $email = (new Email())
            ->to(new Address(self::DEBUG_RECIPIENT))
            ->subject($subject)
            ->text(
                join(
                    "\n",
                    $rows
                )
            )
            ->html(
                '<table>'
                    .join(
                        "\n",
                        $htmlRows
                    )
                    .'</table>'
            );

       // try send
       try {
            $this->mailer->send($email);

        // catch e
       } catch (TransportExceptionInterface $e) {
            # @TODO Logging
       }
    }


    /**
     * send info mail
     *
     * @param array $data
     * @param string $template
     *
     * @return void
     */
    public function infoMail(array $data, string $template = 'default'): void
    {
        $subject = ($data['subject'] ?? 'No subject');

        // mail
        $email = (new TemplatedEmail())
           ->to(new Address(
                $data['recipientEmail'],
                $data['recipientName']
               )
            )
            ->subject($subject)

            ->htmlTemplate('mail/'.$template.'.html.twig')
#            ->textTemplate('mail/'.$template.'.txt.twig')
            ->context($data);

        // log
        $logEntry = new LogEntry(
            MailService::class,
            0,
            'mail_sent',
            $data['recipientEmail'],
            LogEntry::SEVERITY_INFO
        );

        // details
        $logEntry->setDetails([
            'template' => $template
        ]);

        // persist to database
        $this->em->persist($logEntry);
        $this->em->flush();

        // try send
        try {
            $this->mailer->send($email);

        // catch e
        } catch (TransportExceptionInterface $e) {
            // log fail
            $logEntry = new LogEntry(
                MailService::class,
                0,
                'mail_failure',
                $data['recipientEmail'],
                LogEntry::SEVERITY_ERROR,
                [
                    'error' => get_class($e),
                    'message' => $e->getMessage(),
                ]
            );

            // persist to database
            $this->em->persist($logEntry);
            $this->em->flush();
        }
    }
}
