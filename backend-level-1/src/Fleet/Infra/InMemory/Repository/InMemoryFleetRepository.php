<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\InMemory\Repository;

use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class InMemoryFleetRepository implements FleetRepositoryInterface
{
    /**
     * @var Fleet[]
     */
    private array $fleets;

    public function __construct()
    {
        $this->fleets = [
            new Fleet(Uuid::fromString('d4d6d0c2-343d-46e8-a450-316d637777bf')),
            new Fleet(Uuid::fromString('8a158ebe-e20c-46be-a9f5-61ce13d5a771')),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function find(UuidInterface $fleet): Fleet
    {
        foreach ($this->fleets as $inMemoryFleet) {
            if (0 === $inMemoryFleet->getId()->compareTo($fleet)) {
                return $inMemoryFleet;
            }
        }

        throw new FleetNotFoundException(sprintf('No fleet found for id %s', $fleet));
    }

    /**
     * {@inheritDoc}
     */
    public function findByUser(UuidInterface $user): Fleet
    {
        foreach ($this->fleets as $fleet) {
            if (0 === $user->compareTo($fleet->getUser())) {
                return $fleet;
            }
        }

        throw new FleetNotFoundException(sprintf('No fleet found for user %s', $user));
    }

    public function persist(Fleet $fleet): void
    {
        foreach ($this->fleets as &$existingFleet) {
            if (0 === $existingFleet->getId()->compareTo($fleet->getId())) {
                $existingFleet = $fleet;

                return;
            }
        }
        unset($existingFleet);

        $this->fleets[] = $fleet;
    }
}
