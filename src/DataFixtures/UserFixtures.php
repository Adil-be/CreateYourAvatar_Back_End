<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private $passwordHasher;
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $admin = new User();
        $admin->setEmail("adil-b@gmail.com");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword(
            $admin,
            '666'
        )
        );

        $TatyJosy = new User();
        $TatyJosy->setRoles(['ROLE_ADMIN']);
        $TatyJosy->setEmail("TatyJosy@gmail.com");
        $TatyJosy->setPassword($this->passwordHasher->hashPassword(
            $TatyJosy,
            '007'
        )
        );

        $toto = new User();
        $toto->setEmail("toto@gmail.com");
        $toto->setPassword($this->passwordHasher->hashPassword(
            $toto,
            '1234'
        )
        );

        $users = [$toto, $TatyJosy, $admin];

        foreach ($users as $user) {

            $manager->persist($user);
        }
        $manager->flush();
    }
}