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

use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\WarehouseDeliveryTimeRuleRepositoryInterface;
use CoreShop\Bundle\RuleBundle\Doctrine\ORM\RuleRepository;

class WarehouseDeliveryTimeRuleRepository extends RuleRepository implements WarehouseDeliveryTimeRuleRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function findForWarehouse(WarehouseInterface $warehouse): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouse->getId())
            ->getQuery()
            ->getResult();
    }
}
