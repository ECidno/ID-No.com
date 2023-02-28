<?php
namespace App\Repository;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\Nutzer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Nutzer repository
 */
class NutzerRepository extends ServiceEntityRepository
{
    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nutzer::class);
    }
}