<?php
namespace App\Repository;

/***********************************************************************
 *
 * (c) 2020 Frank Krüger <fkrueger@mp-group.net>, mp group GmbH
 *
 /*********************************************************************/

use App\Entity\LogEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Log entry repository
 */
class LogEntryRepository extends ServiceEntityRepository
{
    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEntry::class);
    }
}
