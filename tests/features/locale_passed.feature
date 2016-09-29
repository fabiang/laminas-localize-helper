Feature: passing locale to instances via initializer

  Scenario: passing locale to validators
    Given locale is "en_US"
    When I request plugin "DateTime" from "ValidatorManager"
    Then Locale of plugin should be "en_US"

  Scenario: passing locale to filters
    Given locale is "de_DE"
    When I request plugin "Alnum" from "FilterManager"
    Then Locale of plugin should be "de_DE"

  Scenario: passing locale to view helpers
    Given locale is "de_DE"
    When I request plugin "DateFormat" from "ViewHelperManager"
    Then Locale of plugin should be "de_DE"
