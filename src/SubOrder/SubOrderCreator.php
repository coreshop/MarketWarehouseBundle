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
use CoreShop\Component\Resource\Service\FolderCreationServiceInterface;

class SubOrderCreator implements SubOrderCreatorInterface
{
    public function __construct(
        protected FactoryInterface $subOrderFactory,
        protected FactoryInterface $subOrderItemFactory,
        protected CartContextResolverInterface $cartContextResolver,
        protected FolderCreationServiceInterface $folderCreationService,
    ) {
    }

    public function createSubOrder(OrderInterface $order): ?SubOrderInterface
    {
        $subOrder = null;
        $totalPriceNet = 0;
        $totalPriceGross = 0;
        $packages = $order->getPackages();

        foreach ($packages as $index => $package) {
            /**
             * @var SubOrderInterface $subOrder
             */
            $subOrder = $this->subOrderFactory->createNew();
            $subOrder->setPublished(true);
            $subOrder->setParent(
                $this->folderCreationService->createFolderForResource(
                    $subOrder,
                    ['prefix' => $order->getFullPath()]
                )
            );

            $subOrder->setKey((string)$index);
            $subOrder->setOrder($order);
            $subOrder->addPackage($package);
            $subOrder->save();

            /** @var OrderPackageItemInterface $item */
            foreach ($package->getItems() as $k => $item) {
                $this->createOrUpdateSubOrderItem($item, $subOrder, (string)$k);

                $totalPriceGross += $item->getSubtotalGross();
                $totalPriceNet += $item->getSubtotalNet();
            }

            $subOrder->setSubtotal($totalPriceGross, true);
            $subOrder->setSubtotal($totalPriceNet, false);
            $subOrder->setShipping($package->getShippingGross(), true);
            $subOrder->setShipping($package->getShippingNet(), false);
            $subOrder->save();
        }

        return $subOrder;
    }

    protected function createOrUpdateSubOrderItem(
        OrderPackageItemInterface $item,
        SubOrderInterface $subOrder,
        string $key,
    ) {
        $quantity = (int)$item->getQuantity();
        $subTotalNet = $item->getSubtotalNet();
        $subTotalGross = $item->getSubtotalGross();

        $subOrderItem = null;
        foreach ($subOrder->getItems() as $existingItem) {
            if ($existingItem->getOrderItem() && $item->getOrderItem() && $existingItem->getOrderItem()->getId() === $item->getOrderItem()->getId()) {
                $subOrderItem = $existingItem;
            }
        }

        if (null === $subOrderItem) {
            /**
             * @var SubOrderItemInterface $subOrderItem
             */
            $subOrderItem = $this->subOrderItemFactory->createNew();
            $subOrderItem->setPublished(true);
            $subOrderItem->setParent(
                $this->folderCreationService->createFolderForResource(
                    $subOrderItem,
                    ['prefix' => $subOrder->getFullPath()]
                )
            );
            $subOrderItem->setKey($key);
            $subOrderItem->setPublished(true);
            $subOrderItem->setProduct($item->getProduct());
            $subOrderItem->setOrderItem($item->getOrderItem());
            $subOrder->addItem($subOrderItem);
        } else {
            $quantity += (int)$subOrderItem->getQuantity();
            $subTotalNet += $subOrderItem->getSubtotalNet();
            $subTotalGross += $subOrderItem->getSubtotalGross();
        }

        $subOrderItem->addPackageItem($item);
        $subOrderItem->setQuantity($quantity);
        $subOrderItem->setSubtotalNet($subTotalNet);
        $subOrderItem->setSubtotalGross($subTotalGross);
        $subOrderItem->save();
    }
}
