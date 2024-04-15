<?php
namespace App\Repository;

/***********************************************************************
 *
 * (c) 2024 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PassEntryCondition;

/**
 * PassEntryCondition repository
 */
class PassEntryConditionRepository extends ServiceEntityRepository
{
    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PassEntryCondition::class);
    }
}
