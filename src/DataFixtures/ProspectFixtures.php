<?php

namespace App\DataFixtures;

use App\Entity\Prospect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProspectFixtures extends Fixture
{
    public function __construct()
    {
    }
    public function load(ObjectManager $manager)
    {
        $propect = new Prospect();
            $propect
                ->setMail("propect@mail.md")
                ->setNom("PropectName")
                ->setRgpd(true)
                ->setStatus("CHAUD")
                ->setDescription("Petit Description/note")
                ->setCreatedAt(new \DateTime('NOW'))
                ->setDisabled(false);
            $manager->persist($propect);
            $manager->flush();
        }
}
