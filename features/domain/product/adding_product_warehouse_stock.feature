@mw_domain @product
Feature: Adding a new Product
  In order to extend my catalog
  I want to create a new product with stock in multiple warehouses

  Background:
    Given the site operates on a store in "Austria"
    And the site has a product "Shoe" priced at 100
    And the site has a supplier "Wolf DE"
    And the supplier has a warehouse "Nord"
    And the supplier has a package-type with identifier "Parcel"
    And the supplier has a warehouse "Süd"

  Scenario: I add product to warehouse Nord
    Given the product has stock of 10 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"
    Given the product has stock of 2 in supplier-warehouse "Wolf DE"->"Süd" with package-type "Parcel"
