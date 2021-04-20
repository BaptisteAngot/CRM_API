<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Client;
use App\Entity\Origine;
use App\Entity\User;
use App\Entity\Prospect;
use App\Entity\RendezVous;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class RendezVousFixtures extends Fixture
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

            $rendezVous = new RendezVous();
            $admin_user = new User();
            $admin_user->setEmail($faker->email);
             $admin_user->setPassword($this->passwordEncoder->encodePassword(
                $admin_user,
                'admin'
            ));
            $admin_user->setFonction("CTO")
                ->setTelephone("0235565941")
                ->setRgpd(true)
                ->setDisabled(false)
                ->setUpdatedAt(new \DateTime())
                ->setCreatedAt(new \DateTime())
                ->setFirstName('Admin')
                ->setLastName('Admin');
            $admin_user->setRoles(array('ROLE_SUPER_ADMIN'));
   
    
            $classic_user = new User();
            $classic_user->setEmail($faker->email);
            $classic_user->setPassword($this->passwordEncoder->encodePassword(
                $classic_user,
                'user'
            ));
            $classic_user->setRoles(array('ROLE_USER'));
            $classic_user->setFonction("DRH")
                ->setTelephone("0235565941")
                ->setRgpd(true)
                ->setDisabled(false)
                ->setUpdatedAt(new \DateTime())
                ->setCreatedAt(new \DateTime())
                ->setFirstName('Sophie')
                ->setLastName('Tilleul');
            

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
      
        
            $propect = new Prospect();
             $origine = new Origine();
             $origine
            ->setNom("Origine1")
              ->setCreatedAt(new \DateTime('NOW'));
           
         
    
            $propect
                    ->setMail($faker->email)
                    ->setNom("PropectName")
                    ->setOrigine($origine)
                    ->setRgpd(true)
                    ->setStatus("CHAUD")
                    ->setDescription("Petit Description/note")
                    ->setCreatedAt(new \DateTime('NOW'))
                    ->setDisabled(false);
               



            $rendezVous->setDateStart($faker->dateTime($max = 'now', $timezone = null))
                ->setDateEnd($faker->dateTime($max = 'now', $timezone = null))
                ->setDescription($faker->text)
                ->setUserIdHost($admin_user)
                ->setClientId( $client)
                ->setProspectId($propect)
                ->setInvitedMail(["blabla@gotmail.fr"]);

            $manager->persist($propect);
            $manager->persist($rendezVous);
            $manager->persist($origine);
            $manager->persist($client);
            $manager->persist($classic_user);
            $manager->persist($admin_user);

        }
        $manager->flush();
    }
}
