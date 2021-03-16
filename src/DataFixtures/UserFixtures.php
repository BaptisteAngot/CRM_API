<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin_user = new User();
        $admin_user->setEmail('admin@admin.com');
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
        $manager->persist($admin_user);

        $classic_user = new User();
        $classic_user->setEmail('user@user.com');
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
        $manager->persist($classic_user);

        $manager->flush();
    }
}
