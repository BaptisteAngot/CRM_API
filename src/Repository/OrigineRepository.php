<?php

namespace App\Repository;

use App\Entity\Origine;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Origine|null find($id, $lockMode = null, $lockVersion = null)
 * @method Origine|null findOneBy(array $criteria, array $orderBy = null)
 * @method Origine[]    findAll()
 * @method Origine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrigineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Origine::class);
        $this->manager = $manager;
    }
    public function saveOrigine($nom)
    {
        $newOrigine = new Origine();

        $newOrigine
            ->setNom($nom)
            ->setCreatedAt(new DateTime('NOW'));


        $this->manager->persist($newOrigine);
        $this->manager->flush();
    }
    public function updateOrigine(Origine $origine): Origine
    {
        $this->manager->persist($origine);
        $origine->setUpdatedAt(new DateTime('NOW'));
        $this->manager->flush();

        return $origine;
    }
    public function removeOrigine(Origine $origine)
    {
        $this->manager->remove($origine);
        $this->manager->flush();
    }

    // /**
    //  * @return Origine[] Returns an array of Origine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Origine
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
