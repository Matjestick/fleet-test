<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\App;

use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

final class VehicleQueries
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @throws VehicleNotFoundException
     */
    public function findVehicle(UuidInterface $vehicleId): Vehicle
    {
        return $this->vehicleRepository->find($vehicleId);
    }

    /**
     * @return array<string, float|null>
     */
    public function getCoordinates(UuidInterface $vehicleId): array
    {
        return ($this->findVehicle($vehicleId))->getCoordinates();
    }
}
