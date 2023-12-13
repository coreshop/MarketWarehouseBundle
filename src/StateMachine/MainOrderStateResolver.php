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

namespace CoreShop\Bundle\MarketWarehouseBundle\StateMachine;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\WorkflowBundle\Manager\StateMachineManagerInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Order\OrderStates;
use CoreShop\Component\Order\OrderTransitions;
use CoreShop\Component\Order\Repository\OrderRepositoryInterface;

class MainOrderStateResolver
{

    public function __construct(
        private StateMachineManagerInterface $stateMachineManager,
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function resolveState(OrderInterface $order): void
    {
        if (!$order instanceof SubOrderInterface) {
            return;
        }

        if (!$order->getIsSuborder()) {
            return;
        }

        $mainOrder = $order->getOrder();

        if (!$mainOrder instanceof OrderInterface) {
            return;
        }

        $subOrders = $this->orderRepository->findBy(['order__id' => $mainOrder->getId(), 'isSuborder' => true]);

        foreach ($subOrders as $subOrder) {
            if (!$subOrder instanceof OrderInterface) {
                continue;
            }

            if ($subOrder->getOrderState() !== OrderStates::STATE_CANCELLED) {
                return;
            }
        }

        $workflow = $this->stateMachineManager->get($mainOrder, OrderTransitions::IDENTIFIER);

        if ($workflow->can($mainOrder, OrderTransitions::TRANSITION_CANCEL)) {
            $workflow->apply($mainOrder, OrderTransitions::TRANSITION_CANCEL);
        }
    }
}