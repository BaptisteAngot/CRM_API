<?php

namespace App\DataFixtures;

use App\Entity\Origine;
use App\Entity\Prospect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProspectFixtures extends Fixture
{
    public function __construct()
    {
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $propect = new Prospect();
        $origine = new Origine();
        $origine
            ->setNom("Origine1")
            ->setCreatedAt(new \DateTime('NOW'));
        $manager->persist($origine);
        $origine2 = new Origine();
        $origine2
            ->setNom("Origine2")
            ->setCreatedAt(new \DateTime('NOW'));
        $manager->persist($origine2);

            $propect
                ->setMail($faker->email)
                ->setNom($faker->name)
                ->setOrigine($origine)
                ->setRgpd(true)
                ->setStatus("CHAUD")
                ->setDescription("Petit Description/note")
                ->setCreatedAt(new \DateTime('NOW'))
                ->setDisabled(false);
            $manager->persist($propect);
            $manager->flush();
        }
}
