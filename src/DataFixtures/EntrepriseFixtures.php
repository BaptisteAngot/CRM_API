<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Entreprise;
use Faker\Factory;

class EntrepriseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {

            $entreprise = new Entreprise();

            $entreprise->setNom($faker->lastName)
                ->setMail($faker->email)
                ->setTel("0102030405")
                ->setAdresse($faker->address)
                ->setCodePostal($faker->postcode)
                ->setVille($faker->city)
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));

            $manager->persist($entreprise);    
        }
        $manager->flush();
    }
}
