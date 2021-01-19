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

namespace CoreShop\Bundle\MarketWarehouseBundle\Pimcore\Repository;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierSaleRuleInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\SupplierSaleRuleRepositoryInterface;
use CoreShop\Bundle\ResourceBundle\Pimcore\PimcoreRepository;
use CoreShop\Component\Store\Model\StoreInterface;

class SupplierSaleRuleRepository extends PimcoreRepository implements SupplierSaleRuleRepositoryInterface
{
    public function findForStore(StoreInterface $store): ?SupplierSaleRuleInterface
    {
        return $this->findOneBy(['store = '.$store->getId()]);
    }

    public function findFallback(): ?SupplierSaleRuleInterface
    {
        return $this->findOneBy(['store IS NULL']);
    }
}
