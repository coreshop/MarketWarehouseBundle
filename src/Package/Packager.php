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
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageItemInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\ProductWarehouseStockInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\ProductWarehouseStockRepositoryInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\SupplierSaleRuleRepositoryInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\WarehouseValidationProcessorInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Core\Provider\AddressProviderInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Model\OrderItemInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;

class Packager implements PackagerInterface
{
    protected SupplierSaleRuleRepositoryInterface $supplierSaleRuleRepository;
    protected ProductWarehouseStockRepositoryInterface $productStockRepository;
    protected FactoryInterface $orderPackageFactory;
    protected FactoryInterface $orderPackageItemFactory;
    protected WarehouseValidationProcessorInterface $warehouseValidationProcessor;
    protected CartContextResolverInterface $cartContextResolver;
    protected AddressProviderInterface $defaultAddressProvider;

    public function __construct(
        SupplierSaleRuleRepositoryInterface $supplierSaleRuleRepository,
        ProductWarehouseStockRepositoryInterface $productStockRepository,
        FactoryInterface $orderPackageFactory,
        FactoryInterface $orderPackageItemFactory,
        WarehouseValidationProcessorInterface $warehouseValidationProcessor,
        CartContextResolverInterface $cartContextResolver,
        AddressProviderInterface $defaultAddressProvider
    ) {
        $this->supplierSaleRuleRepository = $supplierSaleRuleRepository;
        $this->productStockRepository = $productStockRepository;
        $this->orderPackageFactory = $orderPackageFactory;
        $this->orderPackageItemFactory = $orderPackageItemFactory;
        $this->warehouseValidationProcessor = $warehouseValidationProcessor;
        $this->cartContextResolver = $cartContextResolver;
        $this->defaultAddressProvider = $defaultAddressProvider;
    }

    public function createOrderPackages(OrderInterface $cart, array $existingPackages): array
    {
        if ($cart instanceof SubOrderInterface && $cart->getIsSuborder()) {
            return [];
        }

        $store = $cart->getStore();

        $rule = $this->supplierSaleRuleRepository->findForStore($store);

        if (!$rule) {
            $rule = $this->supplierSaleRuleRepository->findFallback();
        }

        $packages = [];
        $warehousePackages = [];

        $address = $cart->getShippingAddress() ?? $this->defaultAddressProvider->getAddress($cart);

        if (null === $address) {
            return [];
        }

        /**
         * @var OrderItemInterface $item
         */
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
                    $supplier1 = $productWarehouseStock->getWarehouse()->getSupplier()->getId();
                    $supplier2 = $productWarehouseStock2->getWarehouse()->getSupplier()->getId();

                    if (!array_key_exists($supplier1, $indices)) {
                        return -1;
                    }

                    if (!array_key_exists($supplier2, $indices)) {
                        return -1;
                    }

                    if ($supplier1 === $supplier2) {
                        return 0;
                    }

                    return $indices[$supplier1] < $indices[$supplier2] ? 1 : -1;
                });

                $stocks = array_filter($stocks, function (ProductWarehouseStockInterface $stock) use ($cart, $address) {
                    return $this->warehouseValidationProcessor->isWarehouseValid(
                        $stock->getWarehouse(),
                        $cart,
                        $address,
                        $this->cartContextResolver->resolveCartContext($cart)
                    );
                });
            }

            $left = (int)$item->getQuantity();

            foreach ($stocks as $stock) {
                $availableStock = $stock->getStock();
                $stockUsed = min($availableStock, $left);
                $left -= $stockUsed;

                //Create new Package, since it cannot be shipped with another one
                if ($stock->getPackageType()->getSingleDeliveryOnline()) {
                    for ($i = 0; $i < $stockUsed; $i++) {
                        $package = $this->createNewPackage($cart, $address, $stock->getWarehouse(), $existingPackages);
                        $packages[] = $package;

                        $this->createPackageItem($item, $package, 1);
                    }

                    continue;
                }

                if (array_key_exists($stock->getWarehouse()->getId(), $warehousePackages)) {
                    /**
                     * @var OrderPackageInterface $package
                     */
                    $package = $warehousePackages[$stock->getWarehouse()->getId()];
                } else {
                    $package = $this->createNewPackage($cart, $address, $stock->getWarehouse(), $existingPackages);

                    $warehousePackages[$stock->getWarehouse()->getId()] = $package;
                    $packages[] = $package;
                }

                $this->createPackageItem($item, $package, (int)$stockUsed);

                if ($left <= 0) {
                    break;
                }
            }

            if ($left > 0) {
                //We have some we cannot deliver, what todo?
                if (array_key_exists('left', $warehousePackages)) {
                    $packageLeft = $warehousePackages['left'];
                } else {
                    $packageLeft = $this->createNewPackage($cart, $address, null, $existingPackages);
                }

                $this->createPackageItem($item, $packageLeft, (int)$left);

                $packages[] = $packageLeft;
            }
        }

        return $packages;
    }

    protected function createPackageItem(
        OrderItemInterface $item,
        OrderPackageInterface $package,
        int $quantity
    ) {
        $packageItem = null;

        foreach ($package->getItems() as $existingPackageItem) {
            if ($existingPackageItem->getOrderItem() && $existingPackageItem->getOrderItem()->getId() === $item->getId()) {
                $packageItem = $existingPackageItem;
            }
        }

        if (null === $packageItem) {
            /**
             * @var OrderPackageItemInterface $packageItem
             */
            $packageItem = $this->orderPackageItemFactory->createNew();
            $packageItem->setPublished(true);
            $packageItem->setOrderItem($item);
            $packageItem->setProduct($item->getProduct());

            $package->addItem($packageItem);
        }

        $packageItem->setQuantity($quantity);
    }

    protected function createNewPackage(
        OrderInterface $cart,
        AddressInterface $address,
        ?WarehouseInterface $warehouse,
        array $existingPackages
    ): OrderPackageInterface {
        foreach ($existingPackages as $existingPackage) {
            $existingWarehouse = $existingPackage->getWarehouse();

            if (null === $existingWarehouse && null === $warehouse) {
                return $existingPackage;
            }

            if (null === $existingWarehouse && null !== $warehouse) {
                continue;
            }

            if (null !== $existingWarehouse && null === $warehouse) {
                continue;
            }

            if ($existingWarehouse->getId() === $warehouse->getId()) {
                return $existingPackage;
            }
        }

        /**
         * @var OrderPackageInterface $package
         */
        $package = $this->orderPackageFactory->createNew();
        $package->setPublished(true);
        $package->setOrder($cart);
        $package->setWarehouse($warehouse);

        //$package->setAddress($address);

        return $package;
    }
}
