<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use App\Entity\Main\LogEntry;
use App\Entity\Nutzer\Nutzer;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Controller\SecurityController;
use Symfony\Component\Security\Http\Event\LogoutEvent;

// class LogoutEventListener
class LogoutEventListener
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
    public function __invoke(LogoutEvent $event)
    {
        /**
         * @var Nutzer
         */
        $user = $event->getToken()->getUser();

        // update log
        $logEntry = new LogEntry(
            SecurityController::class,
            $user->getId(),
            'logout',
            $user->getUsername()
        );

         // persist to database
#        $this->emDefault->persist($logEntry);
#        $this->emDefault->flush();
    }
}
