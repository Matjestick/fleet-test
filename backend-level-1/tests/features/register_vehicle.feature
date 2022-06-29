Feature: Register a vehicle

  In order to follow many vehicles with my application
  As an application user
  I should be able to register my vehicle

  @critical
  Scenario: I can register a vehicle
    Given my fleet
    And a vehicle
    When I register this vehicle into my fleet
    Then this vehicle should be part of my vehicle fleet