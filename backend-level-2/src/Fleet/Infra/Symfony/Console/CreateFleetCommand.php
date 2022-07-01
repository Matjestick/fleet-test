<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\Symfony\Console;

use FleetVehicle\Fleet\App\FleetCommands;
use FleetVehicle\Fleet\App\FleetQueries;
use FleetVehicle\Fleet\Domain\Exception\UserAlreadyHasFleet;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

final class CreateFleetCommand extends Command
{
    public function __construct(private FleetCommands $fleetCommands, private FleetQueries $fleetQueries)
    {
        parent::__construct('fleet-vehicle:fleet:create');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create a fleet for user')
            ->addArgument(name: 'userId', mode: InputOption::VALUE_REQUIRED, description: 'User id. Must be Uuid V4 compatible string')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ?string $userIdInput */
        $userIdInput = $input->getArgument('userId');

        if (null === $userIdInput || !Uuid::isValid($userIdInput)) {
            $output->writeln('User id must be Uuid V4 compatible string');

            return self::FAILURE;
        }

        try {
            $this->fleetCommands->createFleet($userId = Uuid::fromString($userIdInput));
            $fleet = $this->fleetQueries->findUserFleet($userId);
        } catch (UserAlreadyHasFleet $exception) {
            $output->writeln($exception->getMessage());

            return self::FAILURE;
        }
        $output->writeln((string) $fleet->getId());

        return self::SUCCESS;
    }
}
