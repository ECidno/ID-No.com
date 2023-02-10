<?php
namespace App\EventSubscriber;

/***********************************************************************
 *
 * (c) 2020 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

/**
 * Subscriber that updates the last activity of the authenticated user
 */
class ActivitySubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $emNutzer;

    /**
     * @var Security
     */
    private $security;


    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     * @param Security $security
     */
    public function __construct(
        ManagerRegistry $registry,
        Security $security
    ) {
        $this->emNutzer = $registry->getManager('nutzer');
        $this->security = $security;
    }


    /**
     * event | onTerminate
     */
    public function onTerminate()
    {
        /**
         * @var Nutzer
         */
        $nutzer = $this->security->getUser();

        // update lastActivity
        if ($nutzer) {
#            $nutzer->updateLastActivityAt();

            // // persist the data to database.
            // $this->emNutzer->persist($nutzer);
            // $this->emNutzer->flush($nutzer);
        }
    }


    /**
     * get subscribed events
     */
    public static function getSubscribedEvents(): array
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default Locale listener
            KernelEvents::TERMINATE => [
                ['onTerminate', 20],
            ],
        ];
    }
}
