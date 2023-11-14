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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ExpressionLanguage;
use Symfony\Component\Workflow\Event\Event;

class CascadeSuborderCallback
{
    public function __construct(
        private ContainerInterface $container,
        private OrderRepositoryInterface $orderRepository,
    )
    {
    }

    public function apply(OrderInterface $order, string $service, string $method): void
    {
        $allArgs = array_slice(func_get_args(), 3);

        if (!$order instanceof SubOrderInterface) {
            return;
        }

        if ($order->getIsSuborder()) {
            return;
        }

        $subOrders = $this->orderRepository->findBy(['order__id' => $order->getId(), 'isSuborder' => true]);

        foreach ($subOrders as $subOrder) {
            $this->call($subOrder, [$service, $method], $allArgs);
        }
    }

    public function call($object, array $callable, array $callableArgs = []): void
    {
        if (is_string($callable[0]) && $this->container->has($callable[0])) {
            $callable[0] = $this->container->get($callable[0]);
        }

        if (empty($callableArgs)) {
            $args = [$object];
        } else {
            $expr = new ExpressionLanguage();
            $args = array_map(
                function (mixed $arg) use ($object, $expr): mixed {
                    if (!is_string($arg)) {
                        return $arg;
                    }

                    return $expr->evaluate($arg, [
                        'object' => $object,
                        'container' => $this->container,
                    ]);
                },
                $callableArgs,
            );
        }

        call_user_func_array($callable, $args);
    }
}