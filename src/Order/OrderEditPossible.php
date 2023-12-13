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

namespace CoreShop\Bundle\MarketWarehouseBundle\Order;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\OrderEditPossibleInterface;

class OrderEditPossible implements OrderEditPossibleInterface
{
    public function __construct(
        private OrderEditPossibleInterface $inner
    ) {
    }

    public function isOrderEditable(OrderInterface $order): bool
    {
        if ($order instanceof SubOrderInterface && $order->getIsSuborder()) {
            return $this->inner->isOrderEditable($order);
        }

        return false;
    }
}
