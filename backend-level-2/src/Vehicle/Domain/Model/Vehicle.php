<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Domain\Model;

use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyAtLocation;
use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use Symfony\Component\Uid\Uuid;

final class Vehicle
{
    private Uuid $id;
    /**
     * @var string[]
     */
    private array $fleets = [];

    public function __construct(private string $plateNumber, ?Uuid $id = null, private ?string $latitude = null, private ?string $longitude = null, private ?string $altitude = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    /**
     * @throws VehicleAlreadyInFleetException
     */
    public function joinFleet(Uuid $fleet): self
    {
        if ($this->inFleet($fleet)) {
            throw new VehicleAlreadyInFleetException(sprintf('Vehicle already in fleet %s', $fleet));
        }

        $this->fleets[] = (string) $fleet;

        return $this;
    }

    public function inFleet(Uuid $fleetId): bool
    {
        foreach ($this->fleets as $fleet) {
            if (0 === (Uuid::fromString($fleet))->compare($fleetId)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws VehicleAlreadyAtLocation
     */
    public function park(string $latitude, string $longitude, ?string $altitude = null): self
    {
        if ($this->getCoordinates() === ['latitude' => $latitude, 'longitude' => $longitude, 'altitude' => $altitude]) {
            throw new VehicleAlreadyAtLocation(sprintf('Vehicle already parked at %s, %s, %s', $latitude, $longitude, $altitude ?? '0'));
        }
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;

        return $this;
    }

    /**
     * @return array<string, string|null>
     */
    public function getCoordinates(): array
    {
        return ['latitude' => $this->latitude, 'longitude' => $this->longitude, 'altitude' => $this->altitude];
    }
}
