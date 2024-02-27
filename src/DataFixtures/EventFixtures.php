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
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::CLOSED])
        ]);

        EventFactory::createOne([
            'name' => 'Randonnée en montagne',
            'startDateTime' => $date,
            'limitRegisterDate' => $limitDate,
            'maxRegisterQty' => 10,
            'registeredUser' => UserFactory::new()->createMany(9),
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::OPEN]),
            'organiser' => $organiserUser,
        ]);

        // création d'un event passé
        EventFactory::createOne([
            'name' => 'Passé',
            'startDateTime' => $now->modify('-1hour'),
            'limitRegisterDate' => $limitDate,
            'duration' => 60,
            'maxRegisterQty' => 10,
            'registeredUser' => UserFactory::new()->createMany(9),
        ]);
        // création d'un event en cours
        $dateDebut = EventFactory::faker()->dateTimeBetween('-1hour', 'now');
        EventFactory::createOne([
            'name' => 'En cours',
            'startDateTime' => $dateDebut,
            'limitRegisterDate' => (clone $dateDebut)->modify('-1month'),
            'duration' => 120,
            'organiser' => $organiserUser,
        ]);
        // création d'un event annulé
        EventFactory::createOne([
            'name' => 'annulé',
            'startDateTime' => $now->modify('+10hour'),
            'limitRegisterDate' => $limitDate,
            'duration' => 60,
            'maxRegisterQty' => 10,
            'registeredUser' => UserFactory::new()->createMany(9),
            'etat' => $manager->getRepository(Etat::class)->findOneBy(['libelle' => ETAT::CANCELLED]),
            'organiser' => $organiserUser,

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
