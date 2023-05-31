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

namespace CoreShop\Bundle\MarketWarehouseBundle\StateMachine;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\WorkflowBundle\Manager\StateMachineManagerInterface;
use Symfony\Component\Workflow\Event\Event;

class CascadeSuborderTransition
{
    public function __construct(
        protected StateMachineManagerInterface $stateMachineManager,
    ) {
    }

    public function apply(SubOrderInterface $object, Event $event, $transition = null, $workflowName = null, $soft = true): void
    {
        if (null === $transition) {
            $transition = $event->getTransition()?->getName();
        }
        if (null === $workflowName) {
            $workflowName = $event->getWorkflowName();
        }

        if (method_exists($object::class, 'getByOrder')) {
            return;
        }

        $subOrders = $object::getByOrder($object);

        foreach ($subOrders as $subOrder) {
            $workflow = $this->stateMachineManager->get($subOrder, $workflowName);

            if ($soft === true) {
                if (!$workflow->can($subOrder, $transition)) {
                    continue;
                }
            }

            $workflow->apply($subOrder, $transition);
        }
    }
}
