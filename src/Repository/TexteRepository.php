<?php

namespace App\Repository;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Nutzer\Texte;

/**
 * Texte repository
 */
class TexteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Texte::class);
    }

    public function getCountries($language = 'de')
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.bez like :land')
            ->andWhere('t.sprache = :language')
            ->setParameter('land', 'land_%')
            ->setParameter('language', $language)
            ->orderBy('t.string', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
