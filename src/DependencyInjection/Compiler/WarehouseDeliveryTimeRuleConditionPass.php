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

namespace CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler;

use CoreShop\Component\Registry\RegisterRegistryTypePass;

final class WarehouseDeliveryTimeRuleConditionPass extends RegisterRegistryTypePass
{
    public const WAREHOUSE_DELIVERY_TIME_PRICE_RULE_CONDITION_TAG = 'coreshop.market_warehouse.warehouse_delivery_time.condition';

    public function __construct()
    {
        parent::__construct(
            'coreshop.registry.market_warehouse.warehouse_delivery_time.conditions',
            'coreshop.form_registry.market_warehouse.warehouse_delivery_time.conditions',
            'coreshop.market_warehouse.warehouse_delivery_time.conditions',
            self::WAREHOUSE_DELIVERY_TIME_PRICE_RULE_CONDITION_TAG
        );
    }
}
