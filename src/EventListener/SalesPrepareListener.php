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

namespace CoreShop\Bundle\MarketWarehouseBundle\EventListener;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\OrderBundle\Events;
use CoreShop\Bundle\WorkflowBundle\StateManager\WorkflowStateInfoManagerInterface;
use CoreShop\Component\Order\OrderStates;
use CoreShop\Component\Order\OrderTransitions;
use CoreShop\Component\Order\Repository\OrderRepositoryInterface;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class SalesPrepareListener implements EventSubscriberInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ArrayTransformerInterface $serializer,
        private WorkflowStateInfoManagerInterface $workflowStateManager,
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::SALE_DETAIL_PREPARE => 'onSaleDetailPrepare',
        ];
    }


    public function onSaleDetailPrepare(GenericEvent $event)
    {
        $order = $event->getSubject();
        $json = $event->getArguments();

        if (!$order instanceof SubOrderInterface) {
            return;
        }

        $data = $this->orderRepository->findBy(['order__id' => $order->getId(), 'isSuborder' => true]);
        $result = [];

        /**
         * @var SubOrderInterface $subOrder
         */
        foreach ($data as $subOrder) {
            $subOrderSerialized = $this->serializer->toArray($subOrder);

            $subOrderSerialized['stateInfo'] = $this->workflowStateManager->getStateInfo(
                OrderTransitions::IDENTIFIER,
                $subOrder->getOrderState() ?? OrderStates::STATE_NEW,
                false
            );

            $subOrderSerialized['transitions'] = $this->workflowStateManager->parseTransitions(
                $subOrder,
                OrderTransitions::IDENTIFIER,
                [
                    'cancel',
                    'confirm',
                    'complete',
                ],
                false
            );
            $subOrderSerialized['carriers'] = array_map(
                function (OrderPackageInterface $package) {
                    return $this->serializer->toArray($package->getCarrier());
                },
                $subOrder->getPackages()
            );
            $subOrderSerialized['warehouses'] = array_map(
                function (OrderPackageInterface $package) {
                    return $this->serializer->toArray($package->getWarehouse());
                },
                $subOrder->getPackages()
            );

            $result[] = $subOrderSerialized;
        }

        $packages = [];

        foreach ($order->getPackages() as $package) {
            $packageSerialized = $this->serializer->toArray($package);

            $packages[] = $packageSerialized;
        }

        $json['subOrders'] = $result;
        $json['isSubOrder'] = $order->getIsSuborder();
        $json['packages'] = $packages;

        $event->setArguments($json);
    }
}
