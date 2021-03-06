<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\App;

use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class VehicleQueries
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehicleNotFoundException
     */
    public function findVehicle(Uuid $vehicleId): Vehicle
    {
        return $this->vehicleRepository->find($vehicleId);
    }

    /**
     * @return array<string, string|null>
     */
    public function getCoordinates(Uuid $vehicleId): array
    {
        return ($this->findVehicle($vehicleId))->getCoordinates();
    }

    /**
     * @throws VehicleNotFoundException
     */
    public function findVehicleFromPlate(string $vehiclePlate): Vehicle
    {
        return $this->vehicleRepository->findByPlate($vehiclePlate);
    }

    /**
     * @throws VehicleNotFoundException
     */
    public function findVehicleFromFleetAndPlate(Uuid $fleetId, string $plateNumber): Vehicle
    {
        return $this->vehicleRepository->findByFromFleetAndPlate($fleetId, $plateNumber);
    }
}
