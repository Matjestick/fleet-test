Feature: Park a vehicle

  I should be able to create a fleet for a given user

  Background:
    Given a user

  @critical
  Scenario: Successfully create a fleet for a user
    When I create a fleet for this user
    Then this user should have a fleet

  Scenario: Only one fleet by user
    And I have created a fleet for this user
    When I try to create a fleet for this user
    Then I should be informed that this user already have a fleet