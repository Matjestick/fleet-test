<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\App;

use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyAtLocation;
use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class VehicleCommands
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehicleNotFoundException
     * @throws VehicleAlreadyInFleetException
     */
    public function joinFleet(Uuid $vehicleId, Uuid $fleetId): void
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        $vehicle->joinFleet($fleetId);
        $this->vehicleRepository->persist($vehicle);
    }

    /**
     * @throws VehicleNotFoundException
     * @throws VehicleAlreadyAtLocation
     */
    public function park(Uuid $vehicleId, string $latitude, string $longitude, ?string $altitude = null): void
    {
        $vehicle = $this->vehicleRepository->find($vehicleId);
        $vehicle->park($latitude, $longitude, $altitude);
        $this->vehicleRepository->persist($vehicle);
    }
}
