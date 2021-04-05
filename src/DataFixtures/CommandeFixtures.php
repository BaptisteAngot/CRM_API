<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Commande;
use App\Entity\ProposedService;
use Faker\Factory;
class CommandeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {

            $Commande = new Commande();


            $Commande->setNumero("123")
                ->setStatus("status")
                ->setPrixTotal(200)
                ->setCodePromo(null)
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));
            $manager->persist($Commande);
        
        }
        $manager->flush();

        for ($i = 0; $i < 10; $i++) {

            $Commande = new Commande();
            $proposedService = new ProposedService();

            $proposedService->setNom($faker->lastName)
                ->setPrixHt("10")
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));

            $Commande->setNumero("123")
                ->setStatus("status")
                ->setPrixTotal(200)
                ->setCodePromo(null)
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->addItem($proposedService);
            $manager->persist($Commande);
         $manager->persist($proposedService);
        }
        $manager->flush();
    }
}
