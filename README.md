CoreShop Market Warehouse Bundle
========================

## Run Tests
```
# Install Pimcore if not done
APP_ENV=test PIMCORE_TEST_DB_DSN=mysql://root:ROOT@mw/pimcore__behat PIMCORE_INSTALL_ADMIN_USERNAME=admin PIMCORE_INSTALL_ADMIN_PASSWORD=admin PIMCORE_INSTALL_MYSQL_HOST_SOCKET=mw PIMCORE_INSTALL_MYSQL_USERNAME=root PIMCORE_INSTALL_MYSQL_PASSWORD=ROOT PIMCORE_INSTALL_MYSQL_DATABASE=pimcore__behat PIMCORE_INSTALL_MYSQL_PORT=3306 PIMCORE_KERNEL_CLASS=Kernel vendor/bin/pimcore-install --ignore-existing-config --env=test --skip-database-config
APP_ENV=test PIMCORE_CLASS_DIRECTORY=var/tmp/behat/var/classes PIMCORE_TEST_DB_DSN=mysql://root:ROOT@mw/pimcore__behat bin/console coreshop:install
APP_ENV=test PIMCORE_CLASS_DIRECTORY=var/tmp/behat/var/classes PIMCORE_TEST_DB_DSN=mysql://root:ROOT@mw/pimcore__behat bin/console coreshop:patch:classes -f
APP_ENV=test PIMCORE_CLASS_DIRECTORY=var/tmp/behat/var/classes PIMCORE_TEST_DB_DSN=mysql://root:ROOT@mw/pimcore__behat bin/console pimcore:bundle:install CoreShopMarketWarehouseBundle

CORESHOP_SKIP_DB_SETUP=1 APP_ENV=test PIMCORE_CLASS_DIRECTORY=var/tmp/behat/var/classes PIMCORE_TEST_DB_DSN=mysql://root:ROOT@mw/pimcore__behat vendor/bin/behat -c behat.yml.dist -p default
```

