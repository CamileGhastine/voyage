<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
            $user = new User();
            $user->setUsername('admin')
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN'])
            ;
            $manager->persist($user);

        for ($i=0; $i < 5; $i++) { 
            $user = new User();
            $user->setUsername($faker->firstName())
            ->setPassword($this->hasher->hashPassword($user, 'toto123'))
            ->setRoles(['ROLE_USER'])
            ;
            $this->addReference('user'.$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
