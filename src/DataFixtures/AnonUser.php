<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnonUser extends Fixture
{
    public const USER_REFERENCE = 'User 1';

    public function load(ObjectManager $manager): void
    {
        $user = new \App\Entity\AnonUser(
            'User 1',
            '123.123.123.123',
            'Console'
        );

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);
    }
}
