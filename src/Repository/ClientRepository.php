<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Client::class);
        $this->manager = $manager;
    }

    public function saveClient($mail, $nom, $prenom, $fonction, $telephone, $rgpd, $idUser)
    {
        $newClient = new Client();
        $newClient
                ->setMail($mail)
                ->setNom($nom)
                ->setPrenom($prenom)
                ->setFonction($fonction)
                ->setTelephone($telephone)
                ->setRgpd($rgpd)
                ->addIdUser($idUser);


        $this->manager->persist($newClient);
        $this->manager->flush();
    }

    public function updateClient(Client $client ): Client
    {
        $this->manager->persist($client);
        $this->manager->flush();
    
        return $client;
    }
    // /**
    //  * @return Client[] Returns an array of Client objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Client
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
