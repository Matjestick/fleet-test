<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Domain\Repository;

use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Exception\UserAlreadyHasFleet;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use Symfony\Component\Uid\Uuid;

interface FleetRepositoryInterface
{
    /**
     * @throws FleetNotFoundException
     */
    public function find(Uuid $fleet): Fleet;

    /**
     * @throws FleetNotFoundException
     */
    public function findByUser(Uuid $user): Fleet;

    public function persist(Fleet $fleet): void;

    /**
     * @throws UserAlreadyHasFleet
     */
    public function create(Uuid $userId): Fleet;
}
