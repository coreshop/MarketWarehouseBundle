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

namespace CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\Condition;

use CoreShop\Bundle\MarketWarehouseBundle\Model\ShippingPackageInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Rule\Condition\ConditionCheckerInterface;

interface WarehouseDeliveryTimeConditionCheckerInterface extends ConditionCheckerInterface
{
    public function isRuleValid(
        ShippingPackageInterface $package,
        AddressInterface $address,
        array $configuration
    ): bool;
}
