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

namespace CoreShop\Bundle\MarketWarehouseBundle\SubOrder;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageItemInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderItemInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;

class SubOrderCreator implements SubOrderCreatorInterface
{
    protected FactoryInterface $subOrderFactory;
    protected FactoryInterface $subOrderItemFactory;
    protected CartContextResolverInterface $cartContextResolver;

    public function __construct(
        FactoryInterface $subOrderFactory,
        FactoryInterface $subOrderItemFactory,
        CartContextResolverInterface $cartContextResolver
    ) {
        $this->subOrderFactory = $subOrderFactory;
        $this->subOrderItemFactory = $subOrderItemFactory;
        $this->cartContextResolver = $cartContextResolver;
    }

    public function createSubOrder(array $packages, OrderInterface $order): SubOrderInterface
    {
        // TODO: implement

        /**
         * @var SubOrderInterface $subOrder
         */
        $subOrder = $this->subOrderFactory->createNew();
        $subOrder->setPublished(true);
        $subOrder->setOrder($order);

        return $subOrder;

        /*foreach ($packages as $package) {

        }

        return $subOrder;*/
    }

    protected function createSubOrderItem(
        OrderPackageItemInterface $item,
        SubOrderInterface $subOrder,
        int $quantity
    ) {
        // TODO: implement

        $subOrderItem = null;

        foreach ($subOrder->getItems() as $existingPackageItem) {
            if ($existingPackageItem->getOrderItem() && $existingPackageItem->getOrderItem()->getId() === $item->getId()) {
                $subOrderItem = $existingPackageItem;
            }
        }

        if (null === $subOrderItem) {
            /**
             * @var SubOrderItemInterface $subOrderItem
             */
            $subOrderItem = $this->subOrderItemFactory->createNew();
            $subOrderItem->setPublished(true);
            //$subOrderItem->setOrderItem($item);

            $subOrder->addItem($subOrderItem);
        }

        $subOrderItem->setQuantity($quantity);
    }
}
