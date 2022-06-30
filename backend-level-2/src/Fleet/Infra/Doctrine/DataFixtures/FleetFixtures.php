<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\Doctrine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use Symfony\Component\Uid\Uuid;

class FleetFixtures extends Fixture
{
    public const MY_USER_ID = 'd4d6d0c2-343d-46e8-a450-316d637777bf';
    public const OTHER_USER_ID = '8a158ebe-e20c-46be-a9f5-61ce13d5a771';

    public function load(ObjectManager $manager): void
    {
        $myFleet = new Fleet(Uuid::fromString(self::MY_USER_ID));
        $otherFleet = new Fleet(Uuid::fromString(self::OTHER_USER_ID));

        $manager->persist($myFleet);
        $manager->persist($otherFleet);
        $manager->flush();
    }
}
