<?php

namespace App\Repository;

use App\Entity\Mecanicien;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mecanicien>
 *
 * @method Mecanicien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mecanicien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mecanicien[]    findAll()
 * @method Mecanicien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MecanicienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mecanicien::class);
    }

//    /**
//     * @return Mecanicien[] Returns an array of Mecanicien objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mecanicien
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
