<?php

namespace App\DataFixtures;

use App\Factory\CityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CityFactory::createMany(20);
    }

}
