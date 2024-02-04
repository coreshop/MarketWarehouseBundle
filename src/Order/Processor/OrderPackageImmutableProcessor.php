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

namespace CoreShop\Bundle\MarketWarehouseBundle\Order\Processor;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderItemInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;

class OrderPackageImmutableProcessor implements CartProcessorInterface
{
    public function __construct(
        private OrderPackageProcessorInterface $orderPackageProcessor,
    ) {
    }

    public function process(OrderInterface $cart): void
    {
        if (!$cart instanceof SubOrderInterface) {
            return;
        }

        if (!$cart->getImmutable()) {
            return;
        }

        //Order Edit is only allowed on SubOrders
        if (!$cart->getIsSuborder()) {
            return;
        }

        $packages = [];

        /**
         * @var SubOrderItemInterface $item
         */
        foreach ($cart->getItems() as $item) {
            $packageItems = $item->getPackageItems();
            $quantity = $item->getQuantity();
            $packageQuantity = array_sum(
                array_map(
                    static fn($packageItem) => array_map(static fn($item) => $item->getQuantity(), $packageItem->getItems()),
                    $packages
                )
            );
            //$packageQuantity = array_sum(array_map(static fn($package) => $package->getQuantity(), $packages));

            //Quantity didn't change, no need to recalculate
            if ($packageQuantity === $quantity) {
                continue;
            }

            //Figure out the package to find
            foreach ($packageItems as $packageItem) {
                if ($packageItem->getProduct()?->getId() === $item->getProduct()?->getId()) {
                    $packageItem->setQuantity($quantity);

                    $packages[$packageItem->getOrderPackage()->getId()] = $packageItem->getOrderPackage();

                    break;
                }
            }
        }

        foreach ($packages as $package) {
            $this->orderPackageProcessor->process($package);
        }
    }
}
