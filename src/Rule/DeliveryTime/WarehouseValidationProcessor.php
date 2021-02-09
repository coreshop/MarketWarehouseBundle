<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime;

use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTime;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTimeRuleInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\Action\WarehouseDeliveryTimeActionProcessorInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Registry\ServiceRegistryInterface;

class WarehouseValidationProcessor implements WarehouseValidationProcessorInterface
{
    protected $actionServiceRegistry;
    protected $warehouseRuleChecker;

    public function __construct(
        ServiceRegistryInterface $actionServiceRegistry,
        WarehouseRuleCheckerInterface $warehouseRuleChecker
    )
    {
        $this->actionServiceRegistry = $actionServiceRegistry;
        $this->warehouseRuleChecker = $warehouseRuleChecker;
    }

    public function isWarehouseValid(
        WarehouseInterface $warehouse,
        OrderInterface $order,
        AddressInterface $address,
        array $context = []
    ): bool {
        $rule = $this->warehouseRuleChecker->isWarehouseValid($warehouse, $order, $address, $context);

        if ($rule instanceof WarehouseDeliveryTimeRuleInterface) {
            $warehouseDeliveryTime = new WarehouseDeliveryTime();

            foreach ($rule->getActions() as $action) {
                $processor = $this->actionServiceRegistry->get($action->getType());

                if ($processor instanceof WarehouseDeliveryTimeActionProcessorInterface) {
                    $processor->calculateDeliveryTime(
                        $warehouse,
                        $warehouseDeliveryTime,
                        $order,
                        $address,
                        $action->getConfiguration() ?? [],
                        $context
                    );

                    if (!$warehouseDeliveryTime->isValid()) {
                        return false;
                    }
                }
            }
        }

        return true;
    }
}
