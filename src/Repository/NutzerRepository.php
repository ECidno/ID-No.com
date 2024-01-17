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

    /**
     * find all nutzer who should receive an account reminder mail
     * 
     * @return array
     */
    public function findForReminderMail(): array
    {
        $monthsAgo = new \DateTime('now');
        $monthsAgo->modify('-3 month');
        $yearAgo = new \DateTime('now');
        $yearAgo->modify('-1 year');

        $qb = $this->createQueryBuilder('n')
            ->join('n.persons', 'p')
            ->where('n.sendInformation = 1')
            ->andWhere('n.informationSendDatum < :monthsAgo')
            ->andWhere('p.lastChangeDatum < :yearAgo')
            ->setParameter('monthsAgo', $monthsAgo)
            ->setParameter('yearAgo', $yearAgo);

        $query = $qb->getQuery();

        return $query->execute();
    }
}