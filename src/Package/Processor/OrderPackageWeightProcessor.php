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

namespace CoreShop\Bundle\MarketWarehouseBundle\Package\Processor;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Product\Model\ProductInterface;

class OrderPackageWeightProcessor implements OrderPackageProcessorInterface
{
    public function process(OrderPackageInterface $package): void
    {
        $totalWeight = 0;

        foreach ($package->getItems() as $item) {
            $product = $item->getProduct();
            $weight = 0;

            if ($product instanceof ProductInterface) {
                $weight = $product->getWeight() * $item->getQuantity();
            }

            $item->setWeight($weight);

            $totalWeight += $weight;
        }

        $package->setWeight($totalWeight);
    }
}
