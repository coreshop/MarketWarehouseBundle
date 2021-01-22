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

final class SupplierShippingRuleActionPass extends RegisterRegistryTypePass
{
    public const SUPPLIER_SHIPPING_RULE_ACTION_TAG = 'coreshop_market_warehouse.supplier_shipping.action';

    public function __construct()
    {
        parent::__construct(
            'coreshop_market_warehouse.registry.supplier_shipping.actions',
            'coreshop_market_warehouse.form_registry.supplier_shipping.actions',
            'coreshop_market_warehouse.supplier_shipping.actions',
            self::SUPPLIER_SHIPPING_RULE_ACTION_TAG
        );
    }
}
