<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Domain\Model;

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

    public function registerVehicle(UuidInterface $vehicle): self
    {
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
