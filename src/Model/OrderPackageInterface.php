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
use CoreShop\Component\Shipping\Model\CarrierInterface;
use CoreShop\Component\Shipping\Model\ShippableInterface;
use CoreShop\Component\Store\Model\StoreAwareInterface;

interface OrderPackageInterface extends PimcoreModelInterface, ShippableInterface, StoreAwareInterface
{
    public function getOrder();

    public function setOrder($cart);

    /**
     * @return WarehouseInterface
     */
    public function getWarehouse();

    public function setWarehouse($warehouse);

    /**
     * @return CarrierInterface
     */
    public function getCarrier();

    public function setCarrier($carrier);

    /**
     * @return OrderPackageItemInterface[]
     */
    public function getItems(): ?array;

    public function setItems($items);


    public function hasItems(): bool;

    public function addItem(OrderPackageItemInterface $item): void;

    public function removeItem(OrderPackageItemInterface $item): void;

    public function hasItem(OrderPackageItemInterface $item): bool;

    public function getShipping(bool $withTax = false);

    public function setShipping(int $shipping, bool $withTax = false);

    public function getShippingNet();

    public function setShippingNet($shippingNet);

    public function getShippingGross();

    public function setShippingGross($shippingGross);

    public function getShippingTime();

    public function setShippingTime($shippingTime);

    public function setWeight($weight);

    public function setSubtotal(int $subtotal, bool $withTax = true);

    public function getSubtotalNet();

    public function setSubtotalNet($subtotalNet);

    public function getSubtotalGross();

    public function setSubtotalGross($subtotalGross);
}
