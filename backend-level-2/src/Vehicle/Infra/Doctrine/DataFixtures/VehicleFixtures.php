<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Infra\Doctrine\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use Symfony\Component\Uid\Uuid;

class VehicleFixtures extends Fixture
{
    public const VEHICLE_ID = '06f65780-cefb-4f87-b5e2-443712214693';

    public function load(ObjectManager $manager): void
    {
        $vehicle = new Vehicle('PLATE-NUMBER', Uuid::fromString(self::VEHICLE_ID));

        $manager->persist($vehicle);
        $manager->flush();
    }
}
