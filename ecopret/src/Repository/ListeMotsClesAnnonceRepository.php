<?php

namespace App\Repository;

use App\Entity\ListeMotsClesAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListeMotsClesAnnonce>
 *
 * @method ListeMotsClesAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeMotsClesAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeMotsClesAnnonce[]    findAll()
 * @method ListeMotsClesAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeMotsClesAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeMotsClesAnnonce::class);
    }

//    /**
//     * @return ListeMotsClesAnnonce[] Returns an array of ListeMotsClesAnnonce objects
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

//    public function findOneBySomeField($value): ?ListeMotsClesAnnonce
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
