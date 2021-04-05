<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\ProposedService;

class ProposedServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {

            $proposedService = new ProposedService();

            $proposedService->setNom($faker->lastName)
                ->setPrixHt("10")
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null));

            $manager->persist($proposedService);
         
        }
        $manager->flush();
    }
}
