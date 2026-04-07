<?php

namespace App\DataFixtures;

use App\Entity\Destination;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DestinationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i=0; $i < 10 ; $i++) { 
            $destination = new Destination;
            $destination->setCountry($faker->country())
            ->setDescription($faker->paragraphs(4, true))
            ->setImageUrl('https://picsum.photos/300/200')
            ;
            $this->addReference('destination' . $i, $destination);
            $manager->persist($destination);
        }
        $manager->flush();
    }
}
