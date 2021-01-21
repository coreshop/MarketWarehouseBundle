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

use CoreShop\Component\Order\Model\OrderItemInterface;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;
use CoreShop\Component\Shipping\Model\ShippableItemInterface;

interface OrderPackageItemInterface extends PimcoreModelInterface, ShippableItemInterface
{
    public function getOrderPackage();

    /**
     * @return OrderItemInterface
     */
    public function getOrderItem();

    public function setOrderItem($cartItem);

    /**
     * @return int
     */
    public function getQuantity();

    public function setQuantity($quantity);

    public function setWidth($width);

    public function setHeight($height);

    public function setDepth($depth);

    public function setWeight($weight);

    public function setSubtotal(int $subtotal, bool $withTax = true);

    public function getSubtotalNet();

    public function setSubtotalNet($subtotalNet);

    public function getSubtotalGross();

    public function setSubtotalGross($subtotalGross);
}
