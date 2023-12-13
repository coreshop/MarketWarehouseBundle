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

class MainOrderItemQuantityProcessor implements CartProcessorInterface
{
    public function process(OrderInterface $cart): void
    {
        if (!$cart instanceof SubOrderInterface) {
            return;
        }

        if (!$cart->getImmutable()) {
            return;
        }

        if ($cart->getIsSuborder()) {
            return;
        }

        $quantityMap = [];
        $itemMap = [];

        foreach ($cart->getPackages() as $package) {
            foreach ($package->getItems() as $item) {
                $itemId = $item->getOrderItem()?->getId();

                if (!$itemId) {
                    continue;
                }

                if (!isset($quantityMap[$itemId])) {
                    $quantityMap[$itemId] = 0;
                    $itemMap[$itemId] = $item->getOrderItem();
                }

                $quantityMap[$itemId] += $item->getQuantity();
            }
        }

        foreach ($quantityMap as $itemId => $quantity) {
            /**
             * @var SubOrderItemInterface $item
             */
            $item = $itemMap[$itemId];
            $item->setQuantity($quantity);
        }
    }
}