Feature: Park a vehicle

  In order to not forget where I've parked my vehicle
  As an application user
  I should be able to indicate my vehicle location

  Background:
    Given my fleet
    And a vehicle
    And I have registered this vehicle into my fleet

  @critical
  Scenario: Successfully park a vehicle
    And a location
    When I park my vehicle at this location
    Then the known location of my vehicle should verify this location