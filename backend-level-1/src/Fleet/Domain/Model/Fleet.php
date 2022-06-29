<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Domain\Model;

use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Fleet
{
    private UuidInterface $id;

    /**
     * @var UuidInterface[]
     */
    private array $vehicles = [];

    public function __construct(protected UuidInterface $user)
    {
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUser(): UuidInterface
    {
        return $this->user;
    }

    /**
     * @throws FleetAlreadyHasVehicleException
     */
    public function registerVehicle(UuidInterface $vehicle): self
    {
        if ($this->hasVehicle($vehicle)) {
            throw new FleetAlreadyHasVehicleException(sprintf('Fleet already has vehicles %s', $vehicle->toString()));
        }

        $this->vehicles[] = $vehicle;

        return $this;
    }

    public function hasVehicle(UuidInterface $vehicleId): bool
    {
        foreach ($this->vehicles as $vehicle) {
            if (0 === $vehicle->compareTo($vehicleId)) {
                return true;
            }
        }

        return false;
    }
}
