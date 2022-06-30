<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\Doctrine\ORM\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FleetVehicle\Fleet\Domain\Exception\FleetNotFoundException;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use FleetVehicle\Fleet\Domain\Repository\FleetRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class FleetRepository implements FleetRepositoryInterface
{
    /**
     * @var EntityRepository<Fleet>
     */
    private EntityRepository $repository;

    public function __construct(private EntityManagerInterface $em)
    {
        $this->repository = $this->em->getRepository(Fleet::class);
    }

    /**
     * {@inheritDoc}
     */
    public function find(Uuid $fleet): Fleet
    {
        if (!($savedFleet = $this->repository->find($fleet)) instanceof Fleet) {
            throw new FleetNotFoundException(sprintf('No fleet found for id %s', $fleet));
        }

        return $savedFleet;
    }

    /**
     * {@inheritDoc}
     */
    public function findByUser(Uuid $user): Fleet
    {
        if (null === $savedFleet = $this->repository->findOneBy(['user' => $user])) {
            throw new FleetNotFoundException(sprintf('No fleet found for user %s', $user));
        }

        return $savedFleet;
    }

    public function persist(Fleet $fleet): void
    {
        $this->em->persist($fleet);
        $this->em->flush();
    }
}
