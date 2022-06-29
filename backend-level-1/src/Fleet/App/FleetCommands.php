<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\App;

use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

final class FleetCommands
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    /**
     * @throws FleetNotFoundException
     */
    public function registerVehicle(UuidInterface $vehicleId, UuidInterface $fleetId): void
    {
        $fleet = $this->fleetRepository->find($fleetId);
        $fleet->registerVehicle($vehicleId);
        $this->fleetRepository->persist($fleet);
    }
}
