<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Domain\Model;

use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Vehicle
{
    private UuidInterface $id;
    /**
     * @var UuidInterface[]
     */
    private array $fleets = [];

    public function __construct(?UuidInterface $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @throws VehicleAlreadyInFleetException
     */
    public function joinFleet(UuidInterface $fleet): self
    {
        if ($this->inFleet($fleet)) {
            throw new VehicleAlreadyInFleetException(sprintf('Vehicle already in fleet %s', $fleet->toString()));
        }

        $this->fleets[] = $fleet;

        return $this;
    }

    public function inFleet(UuidInterface $fleetId): bool
    {
        foreach ($this->fleets as $fleet) {
            if (0 === $fleet->compareTo($fleetId)) {
                return true;
            }
        }

        return false;
    }
}
