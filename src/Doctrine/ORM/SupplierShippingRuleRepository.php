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

namespace CoreShop\Bundle\MarketWarehouseBundle\Doctrine\ORM;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierCarrierInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\SupplierShippingRuleRepositoryInterface;
use CoreShop\Bundle\RuleBundle\Doctrine\ORM\RuleRepository;

class SupplierShippingRuleRepository extends RuleRepository implements SupplierShippingRuleRepositoryInterface
{
    public function findForSupplierCarrier(SupplierCarrierInterface $supplierCarrier): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.supplierCarrier = :supplierCarrierId')
            ->setParameter('supplierCarrierId', $supplierCarrier->getId())
            ->getQuery()
            ->getResult();
    }
}
