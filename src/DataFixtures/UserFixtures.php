<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::faker()->unique($reset = true);
        UserFactory::createMany(20);
        UserFactory::createOne([
            'email' => 'test@test.com',
            'firstname' => 'test',
            'name' => 'Test',
            'username' => 'testuser',
            'isActive' => 1,
        ]);
    }
}
