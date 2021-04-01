<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Client;

class ClientFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {

            $client = new Client();

            $client->setMail($faker->email)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setFonction($faker->jobTitle)
                ->setTelephone("0102030405")
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setRgpd(random_int(0,1))
                ->setDisabled(random_int(0,1));
            $manager->persist($client);
         
        }
        $manager->flush();
    }
}
