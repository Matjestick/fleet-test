<?php

declare(strict_types=1);

namespace FleetVehicle\Tests\Behat;

use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use FleetVehicle\Fleet\App\FleetCommands;
use FleetVehicle\Fleet\App\FleetQueries;
use FleetVehicle\Fleet\Domain\Exception\FleetAlreadyHasVehicleException;
use FleetVehicle\Fleet\Domain\Exception\UserAlreadyHasFleet;
use FleetVehicle\Fleet\Domain\Model\Fleet;
use FleetVehicle\Fleet\Infra\Doctrine\DataFixtures\FleetFixtures;
use FleetVehicle\Vehicle\App\VehicleCommands;
use FleetVehicle\Vehicle\App\VehicleQueries;
use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyAtLocation;
use FleetVehicle\Vehicle\Domain\Exception\VehicleAlreadyInFleetException;
use FleetVehicle\Vehicle\Domain\Model\Vehicle;
use FleetVehicle\Vehicle\Infra\Doctrine\DataFixtures\VehicleFixtures;
use Symfony\Component\Uid\Uuid;

final class FeatureContext implements Context
{
    private Fleet $myFleet;
    private Fleet $otherFleet;
    private Vehicle $vehicle;
    private Uuid $user;
    /**
     * @var string[]
     */
    private array $location;

    /**
     * @param \Exception[] $exceptions
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private FleetQueries $fleetQueries,
        private FleetCommands $fleetCommands,
        private VehicleQueries $vehicleQueries,
        private VehicleCommands $vehicleCommands,
        private array $exceptions = []
    ) {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropDatabase();
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }

    /**
     * @BeforeScenario
     */
    public function clearData(): void
    {
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();
    }

    /**
     * @BeforeScenario @fleetFixtures
     */
    public function fleetFixtures(): void
    {
        (new VehicleFixtures())->load($this->entityManager);
        (new FleetFixtures())->load($this->entityManager);
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
     * @Given a location
     */
    public function aLocation(): void
    {
        $this->location = ['latitude' => '10.049567988755534', 'longitude' => '53.462766759577057', 'altitude' => '53.462766759577057'];
    }

    /**
     * @Given a user
     */
    public function aUser(): void
    {
        $this->user = Uuid::fromString('8d0440e5-bf98-42db-a785-a945e95a04e0');
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
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUserSFleet(): void
    {
        $this->registerVehicleIntoFleet($this->vehicle->getId(), $this->otherFleet->getId());
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
     * @Given my vehicle has been parked into this location
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation(): void
    {
        $this->vehicleCommands->park($this->vehicle->getId(), $this->location['latitude'], $this->location['longitude'], $this->location['altitude']);
    }

    /**
     * @Given I have created a fleet for this user
     * @When I create a fleet for this user
     */
    public function iCreateAFleetForThisUser(): void
    {
        $this->fleetCommands->createFleet($this->user);
    }

    /**
     * @When I try to create a fleet for this user
     */
    public function iTryToCreateAFleetForThisUser(): void
    {
        try {
            $this->fleetCommands->createFleet($this->user);
        } catch (UserAlreadyHasFleet $exception) {
            $this->exceptions[] = $exception;
        }
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation(): void
    {
        try {
            $this->vehicleCommands->park($this->vehicle->getId(), $this->location['latitude'], $this->location['longitude'], $this->location['altitude']);
        } catch (VehicleAlreadyAtLocation $exception) {
            $this->exceptions[] = $exception;
        }
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

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation(): void
    {
        \PHPUnit\Framework\Assert::assertEquals($this->location, $this->vehicleQueries->getCoordinates($this->vehicle->getId()));
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation(): void
    {
        \PHPUnit\Framework\Assert::assertInstanceOf(VehicleAlreadyAtLocation::class, $this->exceptions[0]);
    }

    /**
     * @Then this user should have a fleet
     */
    public function thisUserShouldHaveAFleet(): void
    {
        \PHPUnit\Framework\Assert::assertInstanceOf(Fleet::class, $this->fleetQueries->findUserFleet($this->user));
    }

    /**
     * @Then I should be informed that this user already have a fleet
     */
    public function iShouldBeInformedThatThisUserAlreadyHaveAFleet(): void
    {
        \PHPUnit\Framework\Assert::assertInstanceOf(UserAlreadyHasFleet::class, $this->exceptions[0]);
    }

    private function registerVehicleIntoFleet(Uuid $vehicleId, Uuid $fleetId): void
    {
        $this->fleetCommands->registerVehicle($vehicleId, $fleetId);
        $this->vehicleCommands->joinFleet($vehicleId, $fleetId);
    }
}
