@mw_domain @packaging
Feature: Adding products from one supplier into the cart
  splits the cart into multiple packages

  Background:
    Given the site operates on a store in "Austria"
    And the site has a tax rate "AT" with "20%" rate
    And the site has a tax rule group "AT"
    And the tax rule group has a tax rule for country "Austria" with tax rate "AT"
    And the site has a product "Heat Pump" priced at 200000
    And the product has the tax rule group "AT"
    And the site has a supplier "Wolf DE"
    And the supplier has a warehouse "Nord"
    And the supplier has a package-type with identifier "Parcel"
    Given the product has stock of 10 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"

  Scenario: Create a new cart and add a product
    Given I add the product "Heat Pump" to my cart
    Then there should be one product in my cart
    And there should be one package for my cart
