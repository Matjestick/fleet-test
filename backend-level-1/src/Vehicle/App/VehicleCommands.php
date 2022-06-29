<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\App;

use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

final class VehicleCommands
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehicleNotFoundException
     * @throws VehicleAlreadyInFleetException
     */
    public function joinFleet(UuidInterface $vehicleId, UuidInterface $fleetId): void
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        $vehicle->joinFleet($fleetId);
        $this->vehicleRepository->persist($vehicle);
    }
}
