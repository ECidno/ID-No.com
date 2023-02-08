<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use App\Entity\Main\LogEntry;
use App\Entity\Nutzer\Nutzer;
use App\Controller\SecurityController;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

// LoginListener
#[AsEventListener]
final class LoginSuccessEventListener
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
    public function __invoke(LoginSuccessEvent $loginSuccessEvent)
    {
        /**
         * @var Nutzer
         */
        $user = $loginSuccessEvent->getUser();

        // update lastLogin
        // $user
        //     ->updateLastLogin()
        //     ->setLoginFail(0);

        // update log
        $logEntry = new LogEntry(
            SecurityController::class,
            $user->getId(),
            'login',
            $user->getUsername()
        );

         // persist to database
#        $this->emDefault->persist($user);
        $this->emDefault->persist($logEntry);
        $this->emDefault->flush();
    }
}
