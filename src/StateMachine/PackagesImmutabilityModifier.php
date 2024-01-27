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

namespace CoreShop\Bundle\MarketWarehouseBundle\StateMachine;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;

class PackagesImmutabilityModifier
{
    public function makeImmutable(\CoreShop\Component\Order\Model\OrderInterface $order): void
    {
        if (!$order instanceof SubOrderInterface) {
            return;
        }

        foreach ($order->getPackages() as $package) {
            $package->setImmutable(true);
            $package->save();

            foreach ($package->getItems() as $item) {
                $item->setImmutable(true);
                $item->save();
            }
        }
    }
}