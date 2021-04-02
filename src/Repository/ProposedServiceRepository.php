<?php

namespace App\Repository;

use App\Entity\ProposedService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProposedService|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProposedService|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProposedService[]    findAll()
 * @method ProposedService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProposedServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProposedService::class);
    }

    // /**
    //  * @return ProposedService[] Returns an array of ProposedService objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProposedService
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
