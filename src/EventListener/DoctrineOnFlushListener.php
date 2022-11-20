<?php
namespace App\EventListener;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer\Person;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Doctrine events | onFlush
 */
class DoctrineOnFlushListener
{
    /**
     * onFlush
     *
     * @param OnFlushEventArgs $args
     * @return void
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof Person) {
                $class = $em->getClassMetadata(Person::class);
                $changeSet = $uow->getEntityChangeSet($entity);


                if (
                    $changeSet &&
                    isset($changeSet['parent']) &&
                    $changeSet['parent'] === null
                ) {

                    /*
                    $uow->recomputeSingleEntityChangeSet(
                        $class,
                        $entity
                    );
                    */
                }
            }
        }
    }
}