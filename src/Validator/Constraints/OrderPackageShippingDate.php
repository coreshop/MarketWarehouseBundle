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

namespace CoreShop\Bundle\MarketWarehouseBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class OrderPackageShippingDate extends Constraint
{
    /**
     * @var string
     */
    public $message;

    public function validatedBy(): string
    {
        return 'coreshop_market_warehouse_order_package_shipping_date';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}