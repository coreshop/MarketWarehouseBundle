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
    And the site has a supplier "Wolf DE"
    And the supplier has a package-type with identifier "Parcel"
    And the supplier has a warehouse "Nord"
    And the supplier has a warehouse "Süd"
    Given the product has stock of 1 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"
    Given the product has stock of 2 in supplier-warehouse "Wolf DE"->"Süd" with package-type "Parcel"

  Scenario: Create a new cart and add a product
    Given I add the product "Heat Pump" to my cart
    Given I add the product "Heat Pump" to my cart
    Given I add the product "Heat Pump" to my cart
    Then there should be one product in my cart
    And there should be 2 packages for my cart
