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

use CoreShop\Component\Order\Model\OrderItemInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

interface SubOrderItemInterface extends PimcoreModelInterface
{
    public function getSubOrder();

    /**
     * @return OrderItemInterface
     */
    public function getOrderItem(): ?OrderItemInterface;

    public function setOrderItem(?OrderItemInterface $cartItem);

    /**
     * @return OrderPackageItemInterface[]
     */
    public function getPackageItems(): ?array;

    public function setPackageItems(array $packages);

    public function getProduct(): ?PurchasableInterface;

    public function setProduct(?PurchasableInterface $product);

    /**
     * @return float|null
     */
    public function getQuantity(): ?float;

    public function setQuantity(?float $quantity);

    public function setSubtotal(int $subtotal, bool $withTax = true);

    public function getSubtotalNet(): int;

    public function setSubtotalNet(int $subtotalNet);

    public function getSubtotalGross(): int;

    public function setSubtotalGross(int $subtotalGross);

    public function hasPackageItems(): bool;

    public function addPackageItem(OrderPackageItemInterface $item): void;

    public function removePackageItem(OrderPackageItemInterface $item): void;

    public function hasPackageItem(OrderPackageItemInterface $item): bool;
}
