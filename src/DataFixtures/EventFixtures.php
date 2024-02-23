<?php

namespace App\DataFixtures;

use App\Factory\EventFactory;
use App\Factory\SiteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        EventFactory::createMany(10);
        
    }
    public function getDependencies()
    {
        return [
            CityFixtures::class,
            EtatFixtures::class,
            PlaceFixtures::class,
     
        ];
    }
}
