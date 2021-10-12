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
use CoreShop\Component\Order\Model\OrderItemUnitInterface;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;
use CoreShop\Component\Shipping\Model\ShippableItemInterface;

interface OrderPackageItemInterface extends PimcoreModelInterface, ShippableItemInterface
{
    public function getOrderPackage();

    /**
     * @return OrderItemInterface
     */
    public function getOrderItem(): ?OrderItemInterface;

    public function setOrderItem(?OrderItemInterface $cartItem);

    /**
     * @return OrderItemUnitInterface[]
     */
    public function getUnits(): ?array;

    /**
     * @var OrderItemUnitInterface[] $units
     */
    public function setUnits(?array $units);

    public function hasUnit(OrderItemUnitInterface $itemUnit): bool;

    public function addUnit(OrderItemUnitInterface $itemUnit): void;

    public function removeUnit(OrderItemUnitInterface $itemUnit): void;

    /**
     * @return float|null
     */
    public function getQuantity(): ?float;

    public function setQuantity(?float $quantity);

    public function setWidth(?float $width);

    public function setHeight(?float $height);

    public function setDepth(?float $depth);

    public function setWeight(?float $weight);

    public function setSubtotal(int $subtotal, bool $withTax = true);

    public function getSubtotalNet(): int;

    public function setSubtotalNet(int $subtotalNet);

    public function getSubtotalGross(): int;

    public function setSubtotalGross(int $subtotalGross);
}
