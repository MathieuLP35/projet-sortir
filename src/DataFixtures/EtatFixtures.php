<?php

namespace App\DataFixtures;

use App\Factory\EtatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EtatFactory::createOne(['libelle' => 'Ouvert']);
        EtatFactory::createOne(['libelle' => 'Annulé']);
        EtatFactory::createOne(['libelle' => 'Fermé']);
      
    }

}
