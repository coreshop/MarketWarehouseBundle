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

namespace CoreShop\Bundle\MarketWarehouseBundle\Repository;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierCarrierInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierShippingRuleInterface;

interface SupplierShippingRuleRepositoryInterface
{
    /**
     * @param SupplierCarrierInterface $supplierCarrier
     *
     * @return SupplierShippingRuleInterface[]
     */
    public function findForSupplierCarrier(SupplierCarrierInterface $supplierCarrier): array;
}
