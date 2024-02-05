<?php

namespace App\Repository;

use App\Entity\ListeDatesAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeDatesAnnonce>
 *
 * @method ListeDatesAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeDatesAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeDatesAnnonce[]    findAll()
 * @method ListeDatesAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeDatesAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeDatesAnnonce::class);
    }

//    /**
//     * @return ListeDatesAnnonce[] Returns an array of ListeDatesAnnonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ListeDatesAnnonce
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
