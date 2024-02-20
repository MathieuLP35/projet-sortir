<?php

namespace App\DataFixtures;

use App\Factory\SiteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SiteFactory::createMany(10);;
    }
}
