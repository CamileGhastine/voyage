<?php

namespace App\DataFixtures;

use App\Entity\Destination;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WishFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
            for ($i=0; $i < 5; $i++) {
                if(rand(0, 5) >= 4) continue;

                $destinationReference = [];
                for ($j=0; $j < 10; $j++) { 
                    $destinationReference[] = 'destination' . $j;
                }

                for ($k=0; $k < rand(1, 5) ; $k++) {
                    $status = rand(0,4) > 3 ? Wish::VISITEE : Wish::REVEE;
                    $wishlist = new Wish();
                    $wishlist->setUser($this->getReference('user'.$i, User::class))
                    ->setStatus($status);

                    $index = array_rand($destinationReference);
                    $destination = $destinationReference[$index];
                    $wishlist->setDestination($this->getReference($destination, Destination::class));
                    unset($destinationReference[$index]);

                    $manager->persist($wishlist);
                } 
            }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            DestinationFixtures::class
        ];
    }
}
