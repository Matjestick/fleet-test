<?php

declare(strict_types=1);

namespace FleetVehicle\Fleet\Infra\Symfony\Console;

use FleetVehicle\Fleet\App\FleetCommands;
use FleetVehicle\Fleet\Domain\Exception\FleetException;
use FleetVehicle\Vehicle\Domain\Exception\VehicleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

final class RegisterVehicleCommand extends Command
{
    public function __construct(private FleetCommands $fleetCommands)
    {
        parent::__construct('fleet-vehicle:fleet:register-vehicle');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Register a vehicle into an existing fleet')
            ->addArgument(name: 'fleetId', mode: InputOption::VALUE_REQUIRED, description: 'Fleet id')
            ->addArgument(name: 'plateNumber', mode: InputOption::VALUE_REQUIRED, description: 'Vehicle plate number')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ?string $fleetIdinput */
        $fleetIdinput = $input->getArgument('fleetId');
        $plateNumber = $input->getArgument('plateNumber');

        if (null === $fleetIdinput || !Uuid::isValid($fleetIdinput)) {
            $output->writeln('Fleet id must be Uuid V4 compatible string');

            return self::FAILURE;
        }

        if (!\is_string($plateNumber) || '' === $plateNumber) {
            $output->writeln('Vehicle plate number must be provided (any non empty string)');

            return self::FAILURE;
        }

        try {
            $this->fleetCommands->registerVehicleFromPlateNumber(Uuid::fromString($fleetIdinput), $plateNumber);
        } catch (FleetException|VehicleException $exception) {
            $output->writeln($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
