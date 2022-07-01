<?php

declare(strict_types=1);

namespace FleetVehicle\Vehicle\Infra\Symfony\Console;

use FleetVehicle\Fleet\Domain\Exception\FleetException;
use FleetVehicle\Vehicle\App\VehicleCommands;
use FleetVehicle\Vehicle\App\VehicleQueries;
use FleetVehicle\Vehicle\Domain\Exception\VehicleException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

final class ParkVehicleCommand extends Command
{
    public function __construct(private VehicleCommands $vehicleCommands, private VehicleQueries $vehicleQueries)
    {
        parent::__construct('fleet-vehicle:vehicle:park');
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Park a vehicle')
            ->addArgument(name: 'fleetId', mode: InputOption::VALUE_REQUIRED, description: 'Fleet id')
            ->addArgument(name: 'plateNumber', mode: InputOption::VALUE_REQUIRED, description: 'Vehicle plate number')
            ->addArgument(name: 'latitude', mode: InputOption::VALUE_REQUIRED, description: 'Latitude')
            ->addArgument(name: 'longitude', mode: InputOption::VALUE_REQUIRED, description: 'Longitude')
            ->addArgument(name: 'altitude', mode: InputOption::VALUE_OPTIONAL, description: 'Altitude')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var ?string $fleetIdinput */
        $fleetIdinput = $input->getArgument('fleetId');
        $plateNumber = $input->getArgument('plateNumber');
        $latitude = $input->getArgument('latitude');
        $longitude = $input->getArgument('longitude');
        $altitude = $input->getArgument('altitude');

        if (null === $fleetIdinput || !Uuid::isValid($fleetIdinput)) {
            $output->writeln('Fleet id must be Uuid V4 compatible string');

            return self::FAILURE;
        }

        if (!\is_string($plateNumber) || '' === $plateNumber) {
            $output->writeln('Vehicle plate number must be provided (any non empty string)');

            return self::FAILURE;
        }

        if (!\is_string($latitude) || 0 === preg_match('/^(-?\d+(\.\d+)?)$/', $latitude)) {
            $output->writeln('Invalid latitude format');

            return self::FAILURE;
        }

        if (!\is_string($longitude) || 0 === preg_match('/^(-?\d+(\.\d+)?)$/', $longitude)) {
            $output->writeln('Invalid longitude format');

            return self::FAILURE;
        }

        /**
         * @phpstan-ignore-next-line
         */
        if (!empty($altitude) && (!\is_string($altitude) || 0 === preg_match('/^(-?\d+(\.\d+)?)$/', $longitude))) {
            $output->writeln('Invalid altitude format');

            return self::FAILURE;
        }

        $latitude = number_format((float) $latitude, 16, '.', '');
        $longitude = number_format((float) $longitude, 16, '.', '');
        if (empty($altitude)) {
            $altitude = null;
        } else {
            /**
             * @phpstan-ignore-next-line
             */
            $altitude = number_format((float) $altitude, 16, '.', '');
        }

        try {
            $vehicleId = $this->vehicleQueries->findVehicleFromFleetAndPlate(Uuid::fromString($fleetIdinput), $plateNumber)->getId();
            $this->vehicleCommands->park($vehicleId, $latitude, $longitude, $altitude);
        } catch (FleetException|VehicleException $exception) {
            $output->writeln($exception->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
