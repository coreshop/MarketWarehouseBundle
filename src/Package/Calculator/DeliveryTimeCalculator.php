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

namespace CoreShop\Bundle\MarketWarehouseBundle\Package\Calculator;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTime;
use CoreShop\Bundle\MarketWarehouseBundle\Rule\Action\WarehouseDeliveryTimeActionProcessorInterface;
use CoreShop\Component\Registry\ServiceRegistryInterface;
use CoreShop\Component\Rule\Condition\RuleValidationProcessorInterface;

class DeliveryTimeCalculator implements DeliveryTimeCalculatorInterface
{
    private $ruleValidationProcessor;
    private $actionServiceRegistry;

    public function __construct(
        RuleValidationProcessorInterface $ruleValidationProcessor,
        ServiceRegistryInterface $actionServiceRegistry
    ) {
        $this->ruleValidationProcessor = $ruleValidationProcessor;
        $this->actionServiceRegistry = $actionServiceRegistry;
    }

    public function calculateDeliveryTime(OrderPackageInterface $package, array $context)
    {
        $warehouse = $package->getWarehouse();
        $rules = $warehouse->getDeliveryTimeRules();

        $deliveryTime = new WarehouseDeliveryTime();

        $packageContext = $context;
        $packageContext['package'] = $package;

        foreach ($rules as $rule) {
            if (!$this->ruleValidationProcessor->isValid($warehouse, $rule, $context)) {
                continue;
            }

            foreach ($rule->getActions() as $action) {
                $processor = $this->actionServiceRegistry->get($action->getType());

                if (!$processor instanceof WarehouseDeliveryTimeActionProcessorInterface) {
                    continue;
                }

                $processor->calculateDeliveryTime(
                    $warehouse,
                    $deliveryTime,
                    $packageContext,
                    $action->getConfiguration()
                );
            }
        }

        $package->setShippingTime($deliveryTime->getDays());
    }
}
