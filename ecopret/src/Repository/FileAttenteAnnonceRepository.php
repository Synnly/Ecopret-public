<?php

namespace App\Repository;

use App\Entity\FileAttenteAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FileAttenteAnnonce>
 *
 * @method FileAttenteAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method FileAttenteAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method FileAttenteAnnonce[]    findAll()
 * @method FileAttenteAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileAttenteAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileAttenteAnnonce::class);
    }

//    /**
//     * @return FileAttenteAnnonce[] Returns an array of FileAttenteAnnonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FileAttenteAnnonce
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
