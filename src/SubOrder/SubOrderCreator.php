<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 CORS GmbH (https://cors.gmbh)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\SubOrder;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageItemInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderItemInterface;
use CoreShop\Bundle\WorkflowBundle\Manager\StateMachineManagerInterface;
use CoreShop\Component\Core\Model\OrderItemInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;
use CoreShop\Component\Order\Factory\AdjustmentFactoryInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\NumberGenerator\NumberGeneratorInterface;
use CoreShop\Component\Order\OrderTransitions;
use CoreShop\Component\Order\Processor\CartProcessorInterface;
use CoreShop\Component\Pimcore\DataObject\ObjectServiceInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use CoreShop\Component\Resource\Service\FolderCreationServiceInterface;

class SubOrderCreator implements SubOrderCreatorInterface
{
    public function __construct(
        protected FactoryInterface $subOrderFactory,
        protected FactoryInterface $orderItemFactory,
        protected CartContextResolverInterface $cartContextResolver,
        protected FolderCreationServiceInterface $folderCreationService,
        protected StateMachineManagerInterface $stateMachineManager,
        protected ObjectServiceInterface $objectService,
        protected NumberGeneratorInterface $numberGenerator,
        protected CartManagerInterface $cartManager,
    ) {
    }

    public function createSubOrder(OrderInterface $order): ?SubOrderInterface
    {
        $subOrder = null;
        /**
         * @var SubOrderInterface $order
         */
        $packages = $order->getPackages();

        foreach ($packages as $index => $package) {
            $orderNumber = $this->numberGenerator->generate($order);

            /**
             * @var SubOrderInterface $subOrder
             */
            $subOrder = $this->subOrderFactory->createNew();
            $subOrder->setPublished(true);
            $subOrder->setParent($this->objectService->createFolderByPath(sprintf('%s/%s', $order->getFullPath(), 'sub_orders')));
            $subOrder->setKey(uniqid((string) ((int) $index + 1), true));
            $subOrder->setOrder($order);
            $subOrder->setOrderNumber($orderNumber);
            $subOrder->setIsSuborder(true);
            $subOrder->addPackage($package);
            $subOrder->setStore($order->getStore());
            $subOrder->setOrderDate($order->getOrderDate());
            $subOrder->setSaleState($order->getSaleState());
            $subOrder->setOrderState($order->getOrderState());
            $subOrder->setPaymentState($order->getPaymentState());
            $subOrder->setShippingState($order->getShippingState());
            $subOrder->setInvoiceState($order->getInvoiceState());
            $subOrder->setBaseCurrency($order->getBaseCurrency());
            $subOrder->setCurrency($order->getCurrency());
            $subOrder->setCustomer($order->getCustomer());
            $subOrder->setShippingAddress($order->getShippingAddress());
            $subOrder->setInvoiceAddress($order->getInvoiceAddress());
            $subOrder->setPaymentProvider($order->getPaymentProvider());
            $subOrder->setLocaleCode($order->getLocaleCode());
            $subOrder->save();

            /** @var OrderPackageItemInterface $item */
            foreach ($package->getItems() as $k => $item) {
                $this->createOrUpdateSubOrderItem($item, $subOrder, (string)$k);
            }

            $subOrder->setShipping($package->getShippingGross(), true);
            $subOrder->setShipping($package->getShippingNet(), false);

            $this->cartManager->persistCart($subOrder);
        }

        return $subOrder;
    }

    protected function createOrUpdateSubOrderItem(
        OrderPackageItemInterface $item,
        SubOrderInterface $subOrder,
        string $key,
    ): void {
        $quantity = (int)$item->getQuantity();
        $subTotalNet = $item->getSubtotalNet();
        $subTotalGross = $item->getSubtotalGross();

        $subOrderItem = null;
        foreach ($subOrder->getItems() as $existingItem) {
            if (!$existingItem instanceof SubOrderItemInterface) {
                continue;
            }

            if ($existingItem->getOrderItem() && $item->getOrderItem() && $existingItem->getOrderItem()->getId() === $item->getOrderItem()->getId()) {
                $subOrderItem = $existingItem;
            }
        }

        if (null === $subOrderItem) {
            /**
             * @var SubOrderItemInterface&OrderItemInterface $subOrderItem
             */
            $subOrderItem = $this->orderItemFactory->createNew();
            $subOrderItem->setPublished(true);
            $subOrderItem->setParent($this->objectService->createFolderByPath(sprintf('%s/%s', $subOrder->getFullPath(), 'items')));
            $subOrderItem->setKey($key);
            $subOrderItem->setPublished(true);
            $subOrderItem->setProduct($item->getProduct());
            $subOrderItem->setOrderItem($item->getOrderItem());
            $subOrder->addItem($subOrderItem);
        } else {
            $quantity += (int)$subOrderItem->getQuantity();
            $subTotalNet += $subOrderItem->getSubtotal(false);
            $subTotalGross += $subOrderItem->getSubtotal(true);
        }

        $subOrderItem->addPackageItem($item);
        $subOrderItem->setQuantity($quantity);
        $subOrderItem->setSubtotal($subTotalNet, false);
        $subOrderItem->setSubtotal($subTotalGross, true);
        $subOrderItem->save();
    }
}
