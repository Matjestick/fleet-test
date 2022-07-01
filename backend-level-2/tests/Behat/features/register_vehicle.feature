Feature: Register a vehicle

  In order to follow many vehicles with my application
  As an application user
  I should be able to register my vehicle

  @critical @fleetFixtures @vehicleFixtures
  Scenario: I can register a vehicle
    Given my fleet
    And a vehicle
    When I register this vehicle into my fleet
    Then this vehicle should be part of my vehicle fleet

  @fleetFixtures @vehicleFixtures
  Scenario: I can't register same vehicle twice
    Given my fleet
    And a vehicle
    And I have registered this vehicle into my fleet
    When I try to register this vehicle into my fleet
    Then I should be informed this vehicle has already been registered into my fleet

  @fleetFixtures @vehicleFixtures
  Scenario: Same vehicle can belong to more than one fleet
    Given my fleet
    And the fleet of another user
    And a vehicle
    And this vehicle has been registered into the other user's fleet
    When I register this vehicle into my fleet
    Then this vehicle should be part of my vehicle fleet

  @fleetFixtures
  Scenario: I can register a unknown vehicle by plate number
    Given my fleet
    And a plate number
    When I register this vehicle by plate number into my fleet
    Then this vehicle has been created
    And this plate number should be part of my fleet

  @fleetFixtures
  Scenario: I can't register same vehicle plate number twice
    Given my fleet
    And a plate number
    And I have registered this vehicle by plate number into my fleet
    When I try to register this vehicle by plate number into my fleet
    Then I should be informed this vehicle has already been registered into my fleet