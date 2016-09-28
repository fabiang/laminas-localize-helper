Feature: passing locale to instances via initializer

  Scenario: passing locale to validators
    Given locale is "en_US"
    When I request validator "DateTime" from "ValidatorManager"
    Then Locale of validator should be "en_US"

  Scenario: passing locale to filters
    Given locale is "de_DE"
    When I request validator "Alnum" from "FilterManager"
    Then Locale of validator should be "de_DE"

  Scenario: passing locale to view helpers
    Given locale is "de_DE"
    When I request validator "DateFormat" from "ViewHelperManager"
    Then Locale of validator should be "de_DE"
