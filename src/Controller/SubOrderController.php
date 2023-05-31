<?php

declare(strict_types=1);

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

namespace CoreShop\Bundle\MarketWarehouseBundle\Controller;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\OrderBundle\Pimcore\Repository\OrderRepository;
use CoreShop\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use CoreShop\Bundle\WorkflowBundle\Manager\StateMachineManagerInterface;
use CoreShop\Bundle\WorkflowBundle\StateManager\WorkflowStateInfoManagerInterface;
use CoreShop\Component\Order\OrderStates;
use CoreShop\Component\Order\OrderTransitions;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SubOrderController
{
    public function __construct(
        protected ArrayTransformerInterface $serializer,
        protected WorkflowStateInfoManagerInterface $workflowStateManager,
        protected ViewHandlerInterface $viewHandler,
        protected OrderRepository $orderRepository,
    )
    {
    }

    public function getSubOrdersForOrder(
        Request $request,
    ) {
        // TODO: needed
        //$this->isGrantedOr403();

        $result = [];
        $orderId = (int)$request->get('id');
        if ($orderId) {
            $data = $this->orderRepository->findBy(['order__id' => $orderId, 'isSuborder' => true]);

            /**
             * @var SubOrderInterface $subOrder
             */
            foreach ($data as $subOrder) {
                $subOrderSerialized = $this->serializer->toArray($subOrder);

                $subOrderSerialized['stateInfo'] = $this->workflowStateManager->getStateInfo(
                    OrderTransitions::IDENTIFIER,
                    $subOrder->getOrderState() ?? OrderStates::STATE_NEW, false
                );

                $subOrderSerialized['transitions'] = $this->workflowStateManager->parseTransitions(
                    $subOrder,
                    OrderTransitions::IDENTIFIER, [
                        'cancel',
                        'confirm',
                        'complete',
                    ], false
                );
                $subOrderSerialized['carriers'] = array_map(
                    function (OrderPackageInterface $package) {
                        return $this->serializer->toArray($package->getCarrier());
                    }, $subOrder->getPackages()
                );
                $subOrderSerialized['warehouses'] = array_map(
                    function (OrderPackageInterface $package) {
                        return $this->serializer->toArray($package->getWarehouse());
                    }, $subOrder->getPackages()
                );
                $result[] = $subOrderSerialized;
            }
        }

        return $this->viewHandler->handle(['success' => true, 'data' => $result]);
    }

    /**
     * TODO: maybe use OrderController::updateOrderStateAction - wrong get param
     */
    public function updateOrderStateAction(
        Request $request,
        StateMachineManagerInterface $stateMachineManager
    ): JsonResponse
    {
        $subOrder = $this->orderRepository->findBy(['id' => $request->get('id')]);
        $transition = $request->get('transition');

        if (!$subOrder instanceof SubOrderInterface) {
            return $this->viewHandler->handle(['success' => false, 'message' => 'invalid suborder']);
        }

        //apply state machine
        $workflow = $stateMachineManager->get($subOrder, OrderTransitions::IDENTIFIER);
        if (!$workflow->can($subOrder, $transition)) {
            return $this->viewHandler->handle(['success' => false, 'message' => 'this transition is not allowed.']);
        }

        $workflow->apply($subOrder, $transition);

        return $this->viewHandler->handle(['success' => true]);
    }

    // TODO: needed?
    protected function getPermission(): string
    {
        return 'coreshop_permission_order_detail';
    }
}
