<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Domain\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class Vehicle
{
    private UuidInterface $id;
    /**
     * @var UuidInterface[]
     */
    private array $fleets;

    public function __construct(?UuidInterface $id = null)
    {
        $this->id = $id ?? Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function joinFleet(UuidInterface $fleetId): self
    {
        $this->fleets[] = $fleetId;

        return $this;
    }
}
