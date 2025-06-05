<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    public function findByFilters(?string $category, ?string $country, int $page = 1, int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('j')
            ->leftJoin('j.categories', 'c')
            ->leftJoin('j.company', 'co')
            ->orderBy('j.createdAt', 'DESC');

        if ($category) {
            $qb->andWhere('c.id = :category')
                ->setParameter('category', $category);
        }

        if ($country) {
            $qb->andWhere('j.country = :country')
                ->setParameter('country', $country);
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $query = $qb->getQuery();
        $paginator = new Paginator($query);

        return [
            'items' => $paginator->getIterator()->getArrayCopy(),
            'total' => count($paginator),
            'page' => $page,
            'limit' => $limit,
        ];
    }

    public function findAllCountries(): array
    {
        $qb = $this->createQueryBuilder('j')
            ->select('DISTINCT j.country')
            ->orderBy('j.country', 'ASC');

        $result = $qb->getQuery()->getResult();
        return array_column($result, 'country');
    }

//    /**
//     * @return Job[] Returns an array of Job objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Job
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
