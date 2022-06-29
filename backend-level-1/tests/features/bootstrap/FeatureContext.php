<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use FleetVehicle\Fleet\App\FleetCommands;
use FleetVehicle\Fleet\App\FleetQueries;
use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use FleetVehicle\Fleet\Infra\InMemory\Repository\InMemoryFleetRepository;
use FleetVehicle\Vehicle\App\VehicleCommands;
use FleetVehicle\Vehicle\App\VehicleQueries;
use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Infra\InMemory\Repository\InMemoryVehicleRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FeatureContext implements Context
{
    private Fleet $myFleet;
    private Fleet $otherFleet;
    private FleetQueries $fleetQueries;
    private Vehicle $vehicle;
    private FleetCommands $fleetCommands;
    private VehicleCommands $vehicleCommands;
    private VehicleQueries $vehicleQueries;

    /**
     * @param \Exception[] $exceptions
     */
    public function __construct(private array $exceptions = [])
    {
        $fleetRepository = new InMemoryFleetRepository();
        $this->fleetQueries = new FleetQueries($fleetRepository);
        $this->fleetCommands = new FleetCommands($fleetRepository);
        $this->vehicleCommands = new VehicleCommands(new InMemoryVehicleRepository());
        $this->vehicleQueries = new VehicleQueries(new InMemoryVehicleRepository());
    }

    /**
     * @Given my fleet
     */
    public function myFleet(): void
    {
        $this->myFleet = $this->fleetQueries->findUserFleet(Uuid::fromString('d4d6d0c2-343d-46e8-a450-316d637777bf'));
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser(): void
    {
        $this->otherFleet = $this->fleetQueries->findUserFleet(Uuid::fromString('8a158ebe-e20c-46be-a9f5-61ce13d5a771'));
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle(): void
    {
        $this->vehicle = $this->vehicleQueries->findVehicle(Uuid::fromString('06f65780-cefb-4f87-b5e2-443712214693'));
    }

    /**
     * @Given I have registered this vehicle into my fleet
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $this->registerVehicleIntoFleet($this->vehicle->getId(), $this->myFleet->getId());
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet(): void
    {
        try {
            $this->fleetCommands->registerVehicle($this->vehicle->getId(), $this->myFleet->getId());
        } catch (FleetAlreadyHasVehicleException $exception) {
            $this->exceptions[] = $exception;
        }

        try {
            $this->vehicleCommands->joinFleet($this->vehicle->getId(), $this->myFleet->getId());
        } catch (VehicleAlreadyInFleetException $exception) {
            $this->exceptions[] = $exception;
        }
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUserSFleet(): void
    {
        $this->registerVehicleIntoFleet($this->vehicle->getId(), $this->otherFleet->getId());
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        \PHPUnit\Framework\Assert::assertTrue($this->fleetQueries->fleetHasVehicle($this->myFleet->getId(), $this->vehicle->getId()));
    }

    /**
     * @Then /^I should be informed this vehicle has already been registered into my fleet$/
     */
    public function iShouldBeInformedThisVehicleHasAlreadyBeenRegisteredIntoMyFleet(): void
    {
        \PHPUnit\Framework\Assert::assertInstanceOf(FleetAlreadyHasVehicleException::class, $this->exceptions[0]);
        \PHPUnit\Framework\Assert::assertInstanceOf(VehicleAlreadyInFleetException::class, $this->exceptions[1]);
    }

    private function registerVehicleIntoFleet(UuidInterface $vehicleId, UuidInterface $fleetId): void
    {
        $this->fleetCommands->registerVehicle($vehicleId, $fleetId);
        $this->vehicleCommands->joinFleet($vehicleId, $fleetId);
    }
}
