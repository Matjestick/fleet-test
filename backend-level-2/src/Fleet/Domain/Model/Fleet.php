<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Domain\Model;

use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use Symfony\Component\Uid\Uuid;

final class Fleet
{
    private Uuid $id;

    /**
     * @var string[]
     */
    private array $vehicles = [];

    public function __construct(protected Uuid $user)
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getUser(): Uuid
    {
        return $this->user;
    }

    /**
     * @throws FleetAlreadyHasVehicleException
     */
    public function registerVehicle(Uuid $vehicle): self
    {
        if ($this->hasVehicle($vehicle)) {
            throw new FleetAlreadyHasVehicleException(sprintf('Fleet already has vehicles %s', $vehicle));
        }

        $this->vehicles[] = (string) $vehicle;

        return $this;
    }

    public function hasVehicle(Uuid $vehicleId): bool
    {
        foreach ($this->vehicles as $vehicle) {
            if (0 === (Uuid::fromString($vehicle))->compare($vehicleId)) {
                return true;
            }
        }

        return false;
    }
}
