CoreShop Market Warehouse Bundle
========================

## Run Tests
```
# Install Pimcore if not done
APP_ENV=test PIMCORE_TEST_DB_DSN=mysql://root:ROOT@coreshop-market-warehouse-bundle-mariadb/pimcore_test vendor/bin/pimcore-install --mysql-host-socket=coreshop-market-warehouse-bundle-mariadb --skip-database-config --ignore-existing-config

CORESHOP_SKIP_DB_SETUP=1 PIMCORE_TEST_DB_DSN=mysql://root:ROOT@coreshop-market-warehouse-bundle-mariadb/pimcore_test vendor/bin/behat -c behat.yml.dist -p default
```

