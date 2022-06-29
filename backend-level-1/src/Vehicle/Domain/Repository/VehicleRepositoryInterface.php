<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Domain\Repository;

use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use Ramsey\Uuid\UuidInterface;

interface VehicleRepositoryInterface
{
    /**
     * @throws VehicleNotFoundException
     */
    public function find(UuidInterface $vehicle): Vehicle;

    public function persist(Vehicle $vehicle): void;
}
