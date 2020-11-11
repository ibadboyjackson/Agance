<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i <= 100; $i++) {
            $property = new Property();
            $property
                ->setTitle($faker->words(3, true))
                ->setDescription($faker->sentence(3, true))
                ->setSurface($faker->numberBetween(20, 300))
                ->setRooms($faker->numberBetween(2, 10))
                ->setBedrooms($faker->numberBetween(2, 20))
                ->setFloor($faker->numberBetween(2, 7))
                ->setPrice($faker->numberBetween(100000, 250000))
                ->setHeat($faker->numberBetween(0, count(Property::HEAT)))
                ->setCity($faker->city)
                ->setAdress($faker->address)
                ->setPostalCode($faker->postcode)
                ->setSold(false);
            $manager->persist($property);

        }
        $manager->flush();
    }
}
