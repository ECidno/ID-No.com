<?php
namespace App\Repository;

/***********************************************************************
 *
 * (c) 2022 mpDevTeam <dev@mp-group.net>, mp group GmbH
 *
 **********************************************************************/

use App\Entity\NutzerAuth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NutzerAuth>
 *
 * @method NutzerAuth|null find($id, $lockMode = null, $lockVersion = null)
 * @method NutzerAuth|null findOneBy(array $criteria, array $orderBy = null)
 * @method NutzerAuth[]    findAll()
 * @method NutzerAuth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutzerAuthRepository extends ServiceEntityRepository
{
    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutzerAuth::class);
    }


    /**
     * add
     *
     * @param NutzerAuth $entity
     * @param bool $flush
     */
    public function add(NutzerAuth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * remove
     *
     * @param NutzerAuth $entity
     * @param bool $flush
     */
    public function remove(NutzerAuth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
