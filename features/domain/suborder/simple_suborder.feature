@mw_domain @suborder
Feature: Adding products from one supplier
  from multiple warehouses into the cart

  Background:
    Given the site operates on a store in "Austria"
    And the site has a country "Germany" with currency "EUR"
    And the country "Germany" is active
    And the site operates on locale "en"
    And the site has a tax rate "AT" with "20%" rate
    And the site has a tax rule group "AT"
    And the tax rule group has a tax rule for country "Austria" with tax rate "AT"
    And the site has a product "T-Shirt" priced at 2000
    And the product has the tax rule group "AT"
    And the site has a product "Cup" priced at 4000
    And the product is published
    And the product has the tax rule group "AT"
    And the site has a customer "some-customer@something.com"
    And the customer "some-customer@something.com" has an address with country "Austria", "4600", "Wels", "Freiung", "9-11/N3"
    And the cart belongs to customer "some-customer@something.com"
    And the site has a supplier "Wolf DE"
    And the supplier has a warehouse "Nord"
    And the supplier has a warehouse "Süd"
    And the supplier has a package-type with identifier "Parcel"
    And the site has a supplier-carrier "Standard" and ships for 10 in currency "EUR" for supplier "Wolf DE"
    And the supplier-carrier "Standard" has tax rule group "AT"
    And the product "T-Shirt" has stock of 10 in supplier-warehouse "Wolf DE"->"Nord" with package-type "Parcel"
    And the product "Cup" has stock of 10 in supplier-warehouse "Wolf DE"->"Süd" with package-type "Parcel"

  Scenario: Create a new order and add a product
    Given I add the product "T-Shirt" to my cart
    And the cart ships to customer "some-customer@something.com" address with postcode "4600"
    And the cart invoices to customer "some-customer@something.com" address with postcode "4600"
    And I create an order from my cart
    Then there should be one suborder for my order
    And the subtotal for suborder 1 from my order is "2000" excluding tax
    And the subtotal for suborder 1 from my order is "2400" including tax
    And the order subtotal should be "2000" excluding tax
    And the order subtotal should be "2400" including tax
    And the package 1 from my order has 1 package items
    And the suborder 1 from my order has 1 suborder items

  Scenario: Create a new order and add two product
    Given I add the product "T-Shirt" to my cart
    And I add the product "Cup" to my cart
    And the cart ships to customer "some-customer@something.com" address with postcode "4600"
    And the cart invoices to customer "some-customer@something.com" address with postcode "4600"
    And I create an order from my cart
    Then there should be two suborders for my order
    And the subtotal for suborder 1 from my order is "2000" excluding tax
    And the subtotal for suborder 1 from my order is "2400" including tax
#    And the shipping for suborder 1 from my order is "1000" excluding tax
#    And the shipping for suborder 1 from my order is "1200" including tax
    And the subtotal for suborder 2 from my order is "4000" excluding tax
    And the subtotal for suborder 2 from my order is "4800" including tax
#    And the shipping for suborder 2 from my order is "1000" excluding tax
#    And the shipping for suborder 2 from my order is "1200" including tax
    And the order subtotal should be "6000" excluding tax
    And the order subtotal should be "7200" including tax
    And the package 1 from my order has 1 package items
    And the suborder 1 from my order has 1 suborder items
    And the package 2 from my order has 1 package items
    And the suborder 2 from my order has 1 suborder items
