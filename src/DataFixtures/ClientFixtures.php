<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {

            $client = new Client();
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword(
               $user,
               'user'
           ));
            $user->setFonction("CTO")
            ->setTelephone("0235565941")
            ->setRgpd(true)
            ->setDisabled(false)
            ->setUpdatedAt(new \DateTime())
            ->setCreatedAt(new \DateTime())
            ->setFirstName('user')
            ->setLastName('user');
        $user->setRoles(array('ROLE_USER'));

            $client->setMail($faker->email)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setFonction($faker->jobTitle)
                ->setTelephone("0102030405")
                ->setCreatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTime($max = 'now', $timezone = null))
                ->setRgpd(random_int(0,1))
                ->setDisabled(random_int(0,1))
                ->addIdUser($user);


               $manager->persist($client);
               $manager->persist($user);
        }
        $manager->flush();
    }
}
