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

use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTimeRuleInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Rule\Condition\RuleValidationProcessorInterface;

class WarehouseRuleChecker implements WarehouseRuleCheckerInterface
{
    protected RuleValidationProcessorInterface $ruleValidationProcessor;

    public function __construct(RuleValidationProcessorInterface $ruleValidationProcessor)
    {
        $this->ruleValidationProcessor = $ruleValidationProcessor;
    }

    public function isWarehouseValid(
        WarehouseInterface $warehouse,
        OrderInterface $order,
        AddressInterface $address,
        array $context = []
    ): ?WarehouseDeliveryTimeRuleInterface
    {
        $rules = $warehouse->getDeliveryTimeRules();

        foreach ($rules as $rule) {
            $isValid = $this->ruleValidationProcessor->isValid($warehouse, $rule, array_merge($context, [
                'order' => $order,
                'address' => $address
            ]));

            if ($isValid === true) {
                return $rule;
            }
        }

        return null;
    }
}
