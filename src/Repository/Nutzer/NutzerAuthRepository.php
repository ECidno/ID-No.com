<?php

namespace App\Repository\Nutzer;

use App\Entity\Nutzer\NutzerAuth;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NutzerAuth::class);
    }

    public function add(NutzerAuth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NutzerAuth $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return NutzerAuth[] Returns an array of NutzerAuth objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NutzerAuth
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
