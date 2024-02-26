<?php

namespace App\DataFixtures;

use App\Factory\EtatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EtatFactory::createOne(['libelle' => 'Créé']);
        EtatFactory::createOne(['libelle' => 'Ouvert']);
        EtatFactory::createOne(['libelle' => 'Fermé']);
        EtatFactory::createOne(['libelle' => 'En cours']);
        EtatFactory::createOne(['libelle' => 'Passé']);
        EtatFactory::createOne(['libelle' => 'Annulé']);
      
    }

}
