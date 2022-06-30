<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\App;

use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class FleetCommands
{
    public function __construct(private FleetRepositoryInterface $fleetRepository)
    {
    }

    /**
     * @throws FleetNotFoundException
     * @throws FleetAlreadyHasVehicleException
     */
    public function registerVehicle(Uuid $vehicleId, Uuid $fleetId): void
    {
        $fleet = $this->fleetRepository->find($fleetId);
        $fleet->registerVehicle($vehicleId);
        $this->fleetRepository->persist($fleet);
    }
}
