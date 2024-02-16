<?php

namespace App\Repository;

use App\Entity\ReponseDevis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReponseDevis>
 *
 * @method ReponseDevis|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReponseDevis|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReponseDevis[]    findAll()
 * @method ReponseDevis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseDevisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReponseDevis::class);
    }

//    /**
//     * @return ReponseDevis[] Returns an array of ReponseDevis objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReponseDevis
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
