<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Infra\InMemory\Repository;

use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class InMemoryVehicleRepository implements VehicleRepositoryInterface
{
    /**
     * @var Vehicle[]
     */
    private array $vehicles;

    public function __construct()
    {
        $this->vehicles = [
            new Vehicle(Uuid::fromString('06f65780-cefb-4f87-b5e2-443712214693')),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function find(UuidInterface $vehicle): Vehicle
    {
        foreach ($this->vehicles as $inMemoryVehicle) {
            if (0 === $inMemoryVehicle->getId()->compareTo($vehicle)) {
                return $inMemoryVehicle;
            }
        }

        throw new VehicleNotFoundException(sprintf('No vehicle found for id %s', $vehicle));
    }

    public function persist(Vehicle $vehicle): void
    {
        foreach ($this->vehicles as &$existingVehicle) {
            if (0 === $existingVehicle->getId()->compareTo($vehicle->getId())) {
                $existingVehicle = $vehicle;

                return;
            }
        }
        unset($existingVehicle);

        $this->vehicles[] = $vehicle;
    }
}
