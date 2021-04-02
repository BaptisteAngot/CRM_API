<?php

namespace App\Repository;

use App\Entity\GestionTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GestionTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionTicket[]    findAll()
 * @method GestionTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GestionTicket::class);
    }

    // /**
    //  * @return GestionTicket[] Returns an array of GestionTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GestionTicket
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
