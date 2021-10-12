@mw_domain @packaging
Feature: Adding products from one supplier
  from multiple warehouses into the cart

  Background:
    Given the site operates on a store in "Austria"
    And the site has a tax rate "AT" with "20%" rate
    And the site has a tax rule group "AT"
    And the tax rule group has a tax rule for country "Austria" with tax rate "AT"
    And the site has a product "Heat Pump" priced at 200000
    And the product has the tax rule group "AT"
    And the site has a product "Boiler" priced at 100000
    And the product has the tax rule group "AT"
    And the site has a supplier "Wolf DE"
    And the supplier has a package-type with identifier "Parcel"
    And the supplier has a warehouse "Nord"
    And the supplier has a warehouse "Süd"
    And the supplier has a warehouse "Ost"
    Given the product "Heat Pump" has stock of 1 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"
    Given the product "Heat Pump" has stock of 2 in supplier-warehouse "Wolf DE"->"Süd" with package-type "Parcel"
    Given the product "Boiler" has stock of 2 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"
    Given the product "Boiler" has stock of 1 in supplier-warehouse "Wolf DE"->"Ost" with package-type "Parcel"

  Scenario: Create a new cart and add one product
    Given I add the product "Heat Pump" x 3 to my cart
    Then there should be one product in my cart
    And there should be 2 packages for my cart

  Scenario: Create a new cart and add multiple products
    Given I add the product "Heat Pump" x 3 to my cart
    And I add the product "Boiler" x 3 to my cart
    Then there should be two products in my cart
    And there should be 3 packages for my cart

  Scenario: Create a new cart, add multiple products and remove them again
    Given I add the product "Heat Pump" x 3 to my cart
    And I add the product "Boiler" x 3 to my cart
    And I remove the product "Boiler" from my cart
    Then there should be one product in my cart
    And there should be 2 packages for my cart
