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

use CoreShop\Bundle\MarketWarehouseBundle\SubOrder\SubOrderStates;
use CoreShop\Bundle\ResourceBundle\Controller\PimcoreController;
use CoreShop\Bundle\WorkflowBundle\StateManager\WorkflowStateInfoManagerInterface;
use JMS\Serializer\ArrayTransformerInterface;
use Symfony\Component\HttpFoundation\Request;

class SubOrderController extends PimcoreController
{
    public function getSubOrdersForOrder(
        Request $request,
        ArrayTransformerInterface $serializer,
        WorkflowStateInfoManagerInterface $workflowStateManager
    ) {
        $this->isGrantedOr403();

        $result = [];
        $orderId = (int)$request->get('id');
        if ($orderId) {
            $data = $this->repository->findBy(['order__id' => $orderId]);

            foreach ($data as $subOrder) {
                $subOrderSerialized = $serializer->toArray($subOrder);
                $subOrderSerialized['stateInfo'] = $workflowStateManager->getStateInfo('coreshop_sub_order',
                    $subOrder->getState() ?? SubOrderStates::STATE_NEW, false);
                $subOrderSerialized['transitions'] = $workflowStateManager->parseTransitions($subOrder,
                    'coreshop_sub_order', [
                        'cancel',
                    ], false);
                $result[] = $subOrderSerialized;
            }
        }

        return $this->viewHandler->handle(['success' => true, 'data' => $result]);
    }

    protected function getPermission(): string
    {
        return 'coreshop_permission_order_detail';
    }
}
