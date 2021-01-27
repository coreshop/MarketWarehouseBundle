@mw_domain @supplier @supplier_package
Feature: Adding a new Supplier
  with multiple warehouses and package-types

  Background:
    Given the site has a supplier "Wolf"
    And the supplier has a warehouse "Nord"

  Scenario: Adding a Package Type
    Given the supplier has a package-type with identifier "Packet"
    Given the supplier has a package-type with identifier "Großware"



