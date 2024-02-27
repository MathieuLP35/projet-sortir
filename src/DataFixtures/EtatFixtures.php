<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Factory\EtatFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        EtatFactory::createOne(['libelle' => Etat::CREATED]);
        EtatFactory::createOne(['libelle' => Etat::OPEN]);
        EtatFactory::createOne(['libelle' => Etat::MYOPEN]);
        EtatFactory::createOne(['libelle' => Etat::CLOSED]);
        EtatFactory::createOne(['libelle' => Etat::IN_PROGRESS]);
        EtatFactory::createOne(['libelle' => Etat::PAST]);
        EtatFactory::createOne(['libelle' => Etat::CANCELLED]);
    }

}
