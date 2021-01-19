<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Package;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\ProductWarehouseStockInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\ProductWarehouseStockRepositoryInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\SupplierSaleRuleRepositoryInterface;
use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Model\CartItemInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use Pimcore\Model\DataObject\Service;

class Packager implements PackagerInterface
{
    protected $supplierSaleRuleRepository;
    protected $productStockRepository;
    protected $orderPackageFactory;
    protected $orderPackageItemFactory;

    public function __construct(
        SupplierSaleRuleRepositoryInterface $supplierSaleRuleRepository,
        ProductWarehouseStockRepositoryInterface $productStockRepository,
        FactoryInterface $orderPackageFactory,
        FactoryInterface $orderPackageItemFactory
    ) {
        $this->supplierSaleRuleRepository = $supplierSaleRuleRepository;
        $this->productStockRepository = $productStockRepository;
        $this->orderPackageFactory = $orderPackageFactory;
        $this->orderPackageItemFactory = $orderPackageItemFactory;
    }

    public function createOrderPackages(CartInterface $cart): array
    {
        $store = $cart->getStore();

        $rule = $this->supplierSaleRuleRepository->findForStore($store);

        if (!$rule) {
            $rule = $this->supplierSaleRuleRepository->findFallback();
        }

        $packages = [];
        $warehousePackages = [];
        $packageNumber = 1;

        //Check for Priorities on Supplier and Warehouse
        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();

            if (!$product instanceof ProductInterface) {
                continue;
            }

            $stocks = $this->productStockRepository->findForProduct($product);

            if ($rule) {
                $indices = [];

                foreach ($rule->getSuppliers() as $priority => $supplier) {
                    $indices[$supplier->getId()] = $priority;
                }

                $stocks = array_values($stocks);

                usort($stocks, static function (
                    ProductWarehouseStockInterface $productWarehouseStock,
                    ProductWarehouseStockInterface $productWarehouseStock2
                ) use ($indices) {
                    if (!array_key_exists($productWarehouseStock->getWarehouse()->getSupplier()->getId(), $indices)) {
                        return -1;
                    }

                    if (!array_key_exists($productWarehouseStock2->getWarehouse()->getSupplier()->getId(), $indices)) {
                        return -1;
                    }

                    if ($productWarehouseStock->getWarehouse()->getSupplier()->getId() === $productWarehouseStock2->getWarehouse()->getSupplier()->getId()) {
                        return 0;
                    }

                    return $indices[$productWarehouseStock->getWarehouse()->getSupplier()->getId()] >
                    $indices[$productWarehouseStock2->getWarehouse()->getSupplier()->getId()] ? 1 : -1;
                });
            }

            print_r(array_map(function(ProductWarehouseStockInterface $productWarehouseStock) {
                return $productWarehouseStock->getWarehouse()->getSupplier()->getName();
            }, $stocks));

            $left = $item->getQuantity();
            $packageItemNumber = 1;
            $stockUsed = 0;

            foreach ($stocks as $stock) {
                $availableStock = $stock->getStock();
                $stockUsed = min($availableStock, $left);
                $left -= $stockUsed;

                //Create new Package, since it cannot be shipped with another one
                if ($stock->getPackageType()->getSingleDeliveryOnline()) {
                    for ($i = 0; $i < $stockUsed; $i++) {
                        $package = $this->createNewPackage($cart, $stock->getWarehouse(), (string)$packageNumber);
                        $packages[] = $package;

                        $this->createPackageItem($item, $package, 1, (string)$packageItemNumber);

                        $packageNumber++;
                    }

                    continue;
                }

                if (array_key_exists($stock->getWarehouse()->getId(), $warehousePackages)) {
                    /**
                     * @var OrderPackageInterface $package
                     */
                    $package = $warehousePackages[$stock->getWarehouse()->getId()];
                } else {
                    $package = $this->createNewPackage($cart, $stock->getWarehouse(), (string)$packageNumber);
                    $packageNumber++;

                    $warehousePackages[$stock->getWarehouse()->getId()] = $package;
                    $packages[] = $package;
                }

                $this->createPackageItem($item, $package, $stockUsed, (string)$packageItemNumber);

                $packageItemNumber++;

                if ($left <= 0) {
                    break;
                }
            }

            if ($left > 0) {
                //We have some we cannot deliver, what todo?
                if (array_key_exists('left', $warehousePackages)) {
                    $packageLeft = $warehousePackages['left'];
                } else {
                    $packageLeft = $this->createNewPackage($cart, null, 'left');
                    $packageNumber++;
                }

                $this->createPackageItem($item, $packageLeft, $left, "1");

                $packages[] = $packageLeft;
            }
        }

        foreach ($packages as $package) {
            $package->save();
        }

        return $packages;
    }

    protected function createPackageItem(
        CartItemInterface $item,
        OrderPackageInterface $package,
        float $quantity,
        string $number
    ) {
        $packageItem = $this->orderPackageItemFactory->createNew();
        $packageItem->setParent(Service::createFolderByPath(sprintf('%s/items', $package->getFullPath())));
        $packageItem->setPublished(true);
        $packageItem->setKey($number . ' - ' . uniqid());
        $packageItem->setQuantity($quantity);
        $packageItem->setOrderItem($item);
        $packageItem->save();

        $package->addItem($packageItem);

    }

    protected function createNewPackage(
        CartInterface $cart,
        ?WarehouseInterface $warehouse,
        string $number
    ): OrderPackageInterface {
        /**
         * @var OrderPackageInterface $package
         */
        $package = $this->orderPackageFactory->createNew();
        $package->setParent(Service::createFolderByPath(sprintf('%s/packages', $cart->getFullPath())));
        $package->setKey($number . '-' . uniqid());
        $package->setPublished(true);
        $package->setOrder($cart);
        $package->setWarehouse($warehouse);
        $package->save();

        return $package;
    }
}
