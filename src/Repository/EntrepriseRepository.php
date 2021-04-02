<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Entreprise::class);
        $this->manager = $manager;
    }

    public function saveEntreprise($mail, $nom,$tel, $adresse, $codePostal, $ville)
    {
        $newEntreprise = new Entreprise();
        $newEntreprise
                ->setNom($nom)
                ->setMail($mail) 
                ->setTel($tel)
                ->setAdresse($adresse)
                ->setCodePostal($codePostal)
                ->setVille($ville);


        $this->manager->persist($newEntreprise);
        $this->manager->flush();
    }
    public function updateEntreprise(Entreprise $entreprise ): Entreprise
    {
        $this->manager->persist($entreprise);
        $this->manager->flush();

        return $entreprise;
    }

    // /**
    //  * @return Entreprise[] Returns an array of Entreprise objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Entreprise
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
