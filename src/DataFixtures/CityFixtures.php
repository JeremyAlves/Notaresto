<?php

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Migrations\Version\Factory as VersionFactory;
use Faker\Factory;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = VersionFactory::create('fr_FR');

        for ($i = 0; $i < 1000; $i++) {
            $city = new City();
            $city->setName( $faker->city );
            $city->setZipcode( $faker->postcode );
            
            $manager->persist($city);
        }

        $manager->flush();
    }
}
