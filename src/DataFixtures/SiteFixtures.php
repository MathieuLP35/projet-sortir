<?php

namespace App\DataFixtures;

use App\Factory\SiteFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SiteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        SiteFactory::createOne(['name_site' => 'ENI Nantes']);
        SiteFactory::createOne(['name_site' => 'ENI Rennes']);
        SiteFactory::createOne(['name_site' => 'ENI NIORT']);
        SiteFactory::createOne(['name_site' => 'ENI LA ROCHE-SUR-YON']);
        SiteFactory::createOne(['name_site' => 'ENI ANGERS']);
    }
}
