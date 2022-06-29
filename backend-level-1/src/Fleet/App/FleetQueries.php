<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\App;

use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

final class FleetQueries
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    /**
     * @throws FleetNotFoundException
     */
    public function findUserFleet(UuidInterface $userId): Fleet
    {
        return $this->fleetRepository->findByUser($userId);
    }

    /**
     * @throws FleetNotFoundException
     */
    public function fleetHasVehicle(UuidInterface $fleetId, UuidInterface $vehicleId): bool
    {
        return $this->fleetRepository->find($fleetId)->hasVehicle($vehicleId);
    }
}
