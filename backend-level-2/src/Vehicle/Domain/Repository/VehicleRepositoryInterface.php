<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Domain\Repository;

use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use Symfony\Component\Uid\Uuid;

interface VehicleRepositoryInterface
{
    /**
     * @throws VehicleNotFoundException
     */
    public function find(Uuid $vehicle): Vehicle;

    public function persist(Vehicle $vehicle): void;

    /**
     * @throws VehicleNotFoundException
     */
    public function findByPlate(string $vehiclePlate): Vehicle;

    public function create(string $vehiclePlate): Vehicle;
}
