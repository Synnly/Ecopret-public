<?php

namespace App\Repository;

use App\Entity\CarteCredit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CarteCredit>
 *
 * @method CarteCredit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CarteCredit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CarteCredit[]    findAll()
 * @method CarteCredit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarteCreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CarteCredit::class);
    }

//    /**
//     * @return CarteCredit[] Returns an array of CarteCredit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CarteCredit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
