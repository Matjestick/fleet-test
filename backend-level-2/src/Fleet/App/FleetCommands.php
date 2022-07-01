<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\App;

use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use FleetVehicle\Vehicle\App\VehicleCommands;
use FleetVehicle\Vehicle\App\VehicleQueries;
use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use Symfony\Component\Uid\Uuid;

final class FleetCommands
{
    public function __construct(private FleetRepositoryInterface $fleetRepository, private VehicleQueries $vehicleQueries, private VehicleCommands $vehicleCommands)
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
        $this->vehicleCommands->joinFleet($vehicleId, $fleetId);
    }

    public function createFleet(Uuid $userId): void
    {
        $this->fleetRepository->persist($this->fleetRepository->create($userId));
    }

    /**
     * @throws VehicleNotFoundException
     * @throws FleetNotFoundException
     * @throws FleetAlreadyHasVehicleException
     */
    public function registerVehicleFromPlateNumber(Uuid $fleetId, string $vehiclePlate): void
    {
        try {
            $vehicle = $this->vehicleQueries->findVehicleFromPlate($vehiclePlate);
        } catch (VehicleNotFoundException) {
            $this->vehicleCommands->registerVehicle($vehiclePlate);
            $vehicle = $this->vehicleQueries->findVehicleFromPlate($vehiclePlate);
        }
        $this->registerVehicle($vehicle->getId(), $fleetId);
    }
}
