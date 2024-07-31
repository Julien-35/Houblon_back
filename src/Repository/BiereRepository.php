<?php

namespace App\Repository;

use App\Entity\Biere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Biere>
 *
 * @method Biere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biere[]    findAll()
 * @method Biere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biere::class);
    }


//    /**
//     * @return Biere[] Returns an array of Biere objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Biere
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
