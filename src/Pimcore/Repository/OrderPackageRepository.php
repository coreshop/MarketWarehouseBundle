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

use CoreShop\Bundle\MarketWarehouseBundle\Repository\OrderPackageRepositoryInterface;
use CoreShop\Bundle\ResourceBundle\Pimcore\PimcoreRepository;
use CoreShop\Component\Order\Model\OrderInterface;

class OrderPackageRepository extends PimcoreRepository implements OrderPackageRepositoryInterface
{
    public function findForOrder(OrderInterface $cart): array
    {
        $list = $this->getList();
        $list->setCondition('order__id = ?', [$cart->getId()]);

        return $list->getObjects();
    }
}
