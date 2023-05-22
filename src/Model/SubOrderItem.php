<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Model;

use CoreShop\Component\Core\Model\OrderItem;

abstract class SubOrderItem extends OrderItem implements SubOrderItemInterface
{
    public function getSubOrder()
    {
        $parent = $this->getParent();

        do {
            if (is_subclass_of($parent, SubOrderInterface::class)) {
                return $parent;
            }
            $parent = $parent->getParent();
        } while ($parent != null);

        throw new \InvalidArgumentException('SubOrder could not be found!');
    }

    public function hasPackageItems(): bool
    {
        return is_array($this->getItems()) && count($this->getItems()) > 0;
    }

    public function addPackageItem(OrderPackageItemInterface $item): void
    {
        $items = $this->getPackageItems();
        $items[] = $item;

        $this->setPackageItems($items);
    }

    public function removePackageItem(OrderPackageItemInterface $item): void
    {
        $items = $this->getPackageItems();

        for ($i = 0, $c = count($items); $i < $c; $i++) {
            $arrayItem = $items[$i];

            if ($arrayItem->getId() === $item->getId()) {
                unset($items[$i]);

                break;
            }
        }

        $this->setPackageItems(array_values($items));
    }

    public function hasPackageItem(OrderPackageItemInterface $item): bool
    {
        $items = $this->getPackageItems();

        for ($i = 0, $c = count($items); $i < $c; $i++) {
            $arrayItem = $items[$i];

            if ($arrayItem->getId() === $item->getId()) {
                return true;
            }
        }

        return false;
    }

}
