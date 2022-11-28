<?php

namespace App\Repository\Nutzer;

use App\Entity\Nutzer\PwdVergessen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PwdVergessen>
 *
 * @method PwdVergessen|null find($id, $lockMode = null, $lockVersion = null)
 * @method PwdVergessen|null findOneBy(array $criteria, array $orderBy = null)
 * @method PwdVergessen[]    findAll()
 * @method PwdVergessen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PwdVergessenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PwdVergessen::class);
    }

    public function add(PwdVergessen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PwdVergessen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PwdVergessen[] Returns an array of PwdVergessen objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PwdVergessen
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
