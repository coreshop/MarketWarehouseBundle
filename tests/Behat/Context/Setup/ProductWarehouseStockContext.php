<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Setup;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\PackageTypeInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\ProductWarehouseStockInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Component\Core\Model\ProductInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use Pimcore\Model\DataObject\Service;

final class ProductWarehouseStockContext implements Context
{
    private $sharedStorage;
    private $factory;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $factory
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->factory = $factory;
    }

    /**
     * @Given /^the (product) has stock of (\d+) in (warehouse) with (package-type)$/
     * @Given /^the (product  [^"]+") has stock of (\d+) in (warehouse) with (package-type)$/
     * @Given /^the (product "[^"]+") has stock of (\d+) in (warehouse "[^"]+") with (package-type)$/
     * @Given /^the (product "[^"]+") has stock of (\d+) in (warehouse "[^"]+") with (package-type "[^"]+")$/
     * @Given /^the (product "[^"]+") has stock of (\d+) in (supplier warehouse "[^"]+") with (package-type "[^"]+")$/
     */
    public function thereIsASupplier(ProductInterface $product, int $stock, WarehouseInterface $warehouse, PackageTypeInterface $packageType)
    {
        /**
         * @var ProductWarehouseStockInterface $productWarehouseStock
         */
        $productWarehouseStock = $this->factory->createNew();

        $productWarehouseStock->setWarehouse($warehouse);
        $productWarehouseStock->setProduct($product);
        $productWarehouseStock->setStock($stock);
        $productWarehouseStock->setPackageType($packageType);
        $productWarehouseStock->setParent(Service::createFolderByPath($product->getFullPath().'/stock'));
        $productWarehouseStock->setKey(Service::getValidKey($warehouse->getIdentifier(), 'object'));
        $productWarehouseStock->save();
    }
}
