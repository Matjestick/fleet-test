<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Infra\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FleetVehicle\Vehicle\Domain\Exception\VehicleNotFoundException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Domain\Repository\VehicleRepositoryInterface;
use Symfony\Component\Uid\Uuid;

final class VehicleRepository implements VehicleRepositoryInterface
{
    /**
     * @var EntityRepository<Vehicle>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(Vehicle::class);
    }

    /**
     * {@inheritDoc}
     */
    public function find(Uuid $vehicle): Vehicle
    {
        if (!($savedVehicle = $this->repository->find($vehicle)) instanceof Vehicle) {
            throw new VehicleNotFoundException(sprintf('No vehicle found for id %s', $vehicle));
        }

        return $savedVehicle;
    }

    public function findByPlate(string $vehiclePlate): Vehicle
    {
        if (!($savedVehicle = $this->repository->findOneBy(['plateNumber' => $vehiclePlate])) instanceof Vehicle) {
            throw new VehicleNotFoundException(sprintf('No vehicle found for plate number %s', $vehiclePlate));
        }

        return $savedVehicle;
    }

    /**
     * {@inheritDoc}
     */
    public function findByFromFleetAndPlate(Uuid $fleetId, string $plateNumber): Vehicle
    {
        if (!($savedVehicle = $this->repository->findOneBy(['fleets' => [$fleetId], 'plateNumber' => $plateNumber])) instanceof Vehicle) {
            throw new VehicleNotFoundException(sprintf('No vehicle found for plate number %s registered in fleet %s', $plateNumber, $fleetId));
        }

        return $savedVehicle;
    }

    public function create(string $vehiclePlate): Vehicle
    {
        return new Vehicle($vehiclePlate);
    }

    public function persist(Vehicle $vehicle): void
    {
        $this->em->persist($vehicle);
        $this->em->flush();
    }
}
