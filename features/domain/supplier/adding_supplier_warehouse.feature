@mw_domain @supplier
Feature: Adding a new Supplier
  with multiple warehouses

  Background:
    Given the site has a supplier "Wolf"

  Scenario: Supplier has one Warehouse
    Given the supplier has a warehouse "Nord"

  Scenario: Supplier has multiple Warehouses
    Given the supplier has a warehouse "Nord"
    Given the supplier has a warehouse "Süd"


