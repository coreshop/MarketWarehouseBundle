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

namespace CoreShop\Bundle\MarketWarehouseBundle\Rule\Condition;

use CoreShop\Bundle\MarketWarehouseBundle\Model\ShippingPackageInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Model\RuleInterface;

abstract class AbstractWarehouseDeliveryTimeConditionChecker implements WarehouseDeliveryTimeConditionCheckerInterface
{
    /**
     * {@inheritdoc}
     */
    public function isValid(ResourceInterface $subject, RuleInterface $rule, array $configuration, $params = []): bool
    {
        if (!$subject instanceof ShippingPackageInterface) {
            throw new \InvalidArgumentException('Shipping Rule Condition $subject needs to be an array with values shippable, address and carrier');
        }

        if (!array_key_exists('address', $params)) {
            throw new \InvalidArgumentException('Shipping Rule Condition $subject needs to be an array with values shippable, address and carrier');
        }

        return $this->isRuleValid($subject, $params['address'], $configuration);
    }
}
