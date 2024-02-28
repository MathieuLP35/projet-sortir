<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\User;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Repository\EtatRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use function Symfony\Component\Clock\now;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $now = new \DateTime();

        $date = new \DateTime();
        $date->modify('+10 day');

        $limitDate = new \DateTime();
        $limitDate->modify('+9 day');

        $organiserUser = $manager->getRepository(User::class)->findOneBy(['email' => 'test@test.com']);

        $etat = $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::CLOSED]);

        EventFactory::createMany(10);

        // création d'un event avec 10 participants max et on y inscrit 5 participants
        EventFactory::createOne([
            'name' => 'Randonnée dans les bois',
            'startDateTime' => $date,
            'limitRegisterDate' => $limitDate,
            'maxRegisterQty' => 10,
            'registeredUser' => UserFactory::new()->createMany(10),
            'organiser' => $organiserUser,
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::CLOSED])
        ]);
        // création d'un event ouvert
        EventFactory::createOne([
            'name' => 'Ouvert',
            'startDateTime' => $date,
            'limitRegisterDate' => $limitDate,
            'maxRegisterQty' => 10,
            'registeredUser' => UserFactory::new()->createMany(9),
            'organiser' => $organiserUser,
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::OPEN])
        ]);

        // création d'un event créé
        EventFactory::createOne([
            'name' => 'créé',
            'startDateTime' => $now->modify('+1 hour'),
            'limitRegisterDate' => $limitDate,
            'duration' => 60,
            'maxRegisterQty' => 10,
            'organiser' => $organiserUser,
            'registeredUser' => UserFactory::new()->createMany(0),
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::CREATED])
        ]);

        // création d'un event passé
        EventFactory::createOne([
            'name' => 'Passé',
            'startDateTime' => $now->modify('-2 hour'),
            'limitRegisterDate' => $limitDate,
            'duration' => 60,
            'maxRegisterQty' => 10,
            'organiser' => $organiserUser,
            'registeredUser' => UserFactory::new()->createMany(9),
        ]);

        // création d'un event en cours
        $dateDebut = EventFactory::faker()->dateTimeBetween('-1hour', 'now');
        EventFactory::createOne([
            'name' => 'En cours',
            'startDateTime' => $dateDebut,
            'limitRegisterDate' => (clone $dateDebut)->modify('-1month'),
            'organiser' => $organiserUser,
            'duration' => 3000

        ]);
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
