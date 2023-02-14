<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use App\Controller\SecurityController;
use App\Entity\Main\LogEntry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

// LoginFailureEventListener
#[AsEventListener]
final class LoginFailureEventListener
{
    /**
     * @var EntityManagerInterface
     */
    private $emDefault;


    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry
    ) {
        $this->emDefault = $registry->getManager('default');
    }


    /**
     * __invoke
     */
    public function __invoke(LoginFailureEvent $event)
    {
        // get exception
        $ex = $event->getException();

        // update log
        $logEntry = new LogEntry(
            SecurityController::class,
            0,
            'authentication_fail',
            'anonymous',
            LogEntry::SEVERITY_ERROR
        );

        // details
        $logEntry->setDetails([
            'exception' => get_class($ex),
            'code' => $ex->getCode(),
            'message' => $ex->getMessage(),
        ]);

        // persist to database
#        $this->emDefault->persist($logEntry);
#        $this->emDefault->flush();
    }
}
