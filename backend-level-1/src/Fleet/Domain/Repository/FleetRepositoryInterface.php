<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Domain\Repository;

use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use Ramsey\Uuid\UuidInterface;

interface FleetRepositoryInterface
{
    /**
     * @throws FleetNotFoundException
     */
    public function find(UuidInterface $fleet): Fleet;

    /**
     * @throws FleetNotFoundException
     */
    public function findByUser(UuidInterface $user): Fleet;

    public function persist(Fleet $fleet): void;
}
