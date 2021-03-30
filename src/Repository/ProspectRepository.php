<?php

namespace App\Repository;

use App\Entity\Prospect;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Prospect|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prospect|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prospect[]    findAll()
 * @method Prospect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProspectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Prospect::class);
        $this->manager = $manager;
    }
    public function saveProspect($mail, $nom, $rgpd, $describtion,$status)
    {
        $newProspect = new Prospect();

        $newProspect
            ->setMail($mail)
            ->setNom($nom)
            ->setRgpd($rgpd)
            ->setDescription($describtion)
            ->setStatus($status)
            ->setCreatedAt(new DateTime('NOW'))
            ->setDisabled(false);

        $this->manager->persist($newProspect);
        $this->manager->flush();
    }
    public function updateProspect(Prospect $prospect): Prospect
    {
        $this->manager->persist($prospect);
        $prospect->setUpdatedAt(new DateTime('NOW'));
        $this->manager->flush();

        return $prospect;
    }
    public function removeProspect(Prospect $prospect)
    {
        $this->manager->remove($prospect);
        $this->manager->flush();
    }

    // /**
    //  * @return Prospect[] Returns an array of Prospect objects
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
    public function findOneBySomeField($value): ?Prospect
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
