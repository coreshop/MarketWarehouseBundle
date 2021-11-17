<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Model;

use CoreShop\Component\Resource\Pimcore\Model\AbstractPimcoreModel;

abstract class OrderPackageItem extends AbstractPimcoreModel implements OrderPackageItemInterface
{
    public function getOrderPackage()
    {
        $parent = $this->getParent();

        do {
            if (is_subclass_of($parent, OrderPackageInterface::class)) {
                return $parent;
            }
            $parent = $parent->getParent();
        } while ($parent != null);

        throw new \InvalidArgumentException('OrderPackage could not be found!');
    }

    public function getTotal(bool $withTax = true)
    {
        return $this->getSubtotal($withTax);
    }

    public function setTotal(int $total, bool $withTax = true)
    {
        $this->setSubtotal($total, $withTax);
    }

    public function getSubtotal($withTax = true)
    {
        return $withTax ? $this->getSubtotalGross() : $this->getSubtotalNet();
    }

    public function setSubtotal(int $subtotal, bool $withTax = false)
    {
        $withTax ? $this->setSubtotalGross($subtotal) : $this->setSubtotalNet($subtotal);
    }
}
