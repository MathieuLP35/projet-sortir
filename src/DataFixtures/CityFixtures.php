<?php

namespace App\DataFixtures;

use App\Factory\CityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        CityFactory::createOne(['name' => 'Rennes', 'postalCode' => '35000']);
        CityFactory::createOne(['name' => 'Saint-Malo', 'postalCode' => '35400']);
        CityFactory::createOne(['name' => 'Dinan', 'postalCode' => '22100']);
        CityFactory::createOne(['name' => 'Dinard', 'postalCode' => '35800']);
        CityFactory::createOne(['name' => 'Combourg', 'postalCode' => '35270']);
        CityFactory::createOne(['name' => 'Bain-de-Bretagne', 'postalCode' => '35470']);
        CityFactory::createOne(['name' => 'Vitré', 'postalCode' => '35500']);
    }

}
