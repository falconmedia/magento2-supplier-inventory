# Magento 2 Module Supplier Inventory

    falconmedia/module-supplier-inventory

 - [Description](#markdown-header-description)
 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Attributes](#markdown-header-attributes)

## Description
Reason for developing this module is because a store owner can sell even the products are temporary out of stock, but are still available at the supplier. Normally your stock status would be set out of stock, or when you allow backorders, the customers will not shown there is a delay at the product page.
 
With the Supplier Inventory extension for Magento 2 you have the possibility to sell products when you don't have them (temporary) available in your own warehouse, but are available at your suppliers warehouse without complaining customers about delay. 

 - When your products are available in your own warehouse, there will be shown `Direcly Available`
 - Are they not available in your own warehouse, but in your suppliers warehouse, the customer will see the mesaage `XX Shipping Days` 


## Main Functionalities
 - Create Multiple Supplier Profiles to show their stock.
 - Map the `sku` and `stock` fields in the profile
 - Show availability at product page 
 - Update Stock through CSV feed (URL) through CLI

## Installation

### Through Zip file

 - Unzip the zip file in `app/code/FalconMedia`
 - Enable the module by running `php bin/magento module:enable FalconMedia_SupplierInventory`
 - Apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`

### Through composer
    composer require falconmedia/module-supplierinventory
    php bin/magento module:enable FalconMedia_SupplierInventory
    php bin/magento setup:upgrade
    php bin/magento cache:flush


## Configuration
 1. Go in your admin dashboard to: 
 `Catalog > Supplier Inventory > Suppliers`
 1. Add New Supplier
 1. Add the Supplier Name as option to the Supplier (`supplier`) Product Attribute
 1. Importing the stock `php bin/magento falconmedia:supplierstock:import <id>` <id> is the Supplier Profile ID
  
## Attributes

 - Product - Supplier (supplier)
 - Product - Supplier Stock (supplier_stock)

TODO:
 - When product aren't available at your warehouse and suppliers warehouse, the product will be set automatically `out of stock`. 
 - Map `Yes / No` to the `1 / 0` into `supplier_stock`
 - Add Minimal Saleable Stock rule for suppliers who reserve stock for their own, or when supplier have 1 item left and you don't want to take the risk it is out of stock at the time you want to purchase the product.
 - Create ViewModel so the delivery time can also use in the Email and Cart/Checkout
