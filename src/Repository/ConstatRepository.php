<?php

namespace App\Repository;

use App\Entity\Constat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\OrderBy;
/**
 * @extends ServiceEntityRepository<Constat>
 *
 * @method Constat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Constat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Constat[]    findAll()
 * @method Constat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConstatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Constat::class);
    }

    
    public function getConstatsWithSorting(?string $sort, ?string $order, ?string $searchTerm): array
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if ($searchTerm) {
            $queryBuilder
                ->where('c.lieu LIKE :searchTerm OR c.id = :searchTermId OR c.conditionroute LIKE :searchTermRoute OR c.date LIKE :searchTermDate OR c.rapportepolice LIKE :searchTermRapportepolice')
                ->setParameter('searchTerm', '%' . $searchTerm . '%')
                ->setParameter('searchTermId', $searchTerm)
                ->setParameter('searchTermRoute', '%' . $searchTerm . '%')
                ->setParameter('searchTermDate', '%' . $searchTerm . '%')
                ->setParameter('searchTermRapportepolice', '%' . $searchTerm . '%');
        }

        // Sorting logic
        if ($sort === 'date' && in_array($order, ['asc', 'desc'])) {
            $queryBuilder->orderBy('c.date', $order);
        }

        return $queryBuilder->getQuery()->getResult();
    }
    
//    /**
//     * @return Constat[] Returns an array of Constat objects
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

//    public function findOneBySomeField($value): ?Constat
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
