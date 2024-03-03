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

    
    public function getConstatsWithSorting(?string $sort, ?string $order): array
{
    $queryBuilder = $this->createQueryBuilder('c');

    // Sorting logic
    if ($sort === 'date' && in_array($order, ['asc', 'desc'])) {
        $queryBuilder->orderBy('c.date', $order);
    }

    return $queryBuilder->getQuery()->getResult();
}
    
public function search(?string $searchTerm): array
{
    $queryBuilder = $this->createQueryBuilder('c');

    if($searchTerm !== null){
        $queryBuilder
            ->where('c.id LIKE :searchTerm OR c.date = :searchTerm OR c.lieu LIKE :searchTerm OR c.description LIKE :searchTerm OR c.rapportepolice LIKE :searchTerm OR c.conditionroute LIKE :searchTerm ')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
           
           ;
        // Add additional conditions for other fields if needed
    
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
