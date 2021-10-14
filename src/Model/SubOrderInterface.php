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

use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;
use CoreShop\Component\Store\Model\StoreAwareInterface;

interface SubOrderInterface extends PimcoreModelInterface, StoreAwareInterface
{
    /**
     * @return OrderPackageInterface[]
     */
    public function getPackages(): ?array;

    public function setPackages(array $packages);

    /**
     * @return SubOrderItemInterface[]
     */
    public function getItems(): ?array;

    public function setItems(array $items);

    public function hasItems(): bool;

    public function addItem(SubOrderItemInterface $item): void;

    public function removeItem(SubOrderItemInterface $item): void;

    public function hasItem(SubOrderItemInterface $item): bool;

    public function getShipping(bool $withTax = false);

    public function setShipping(int $shipping, bool $withTax = false);

    public function getShippingNet(): int;

    public function setShippingNet(int $shippingNet);

    public function getShippingGross(): int;

    public function setShippingGross(int $shippingGross);

    public function setSubtotal(int $subtotal, bool $withTax = true);

    public function getSubtotalNet(): int;

    public function setSubtotalNet(int $subtotalNet);

    public function getSubtotalGross(): int;

    public function setSubtotalGross(int $subtotalGross);
}
