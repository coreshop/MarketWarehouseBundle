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

interface SubOrderItemInterface extends OrderItemInterface
{
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

    public function hasPackageItems(): bool;

    public function addPackageItem(OrderPackageItemInterface $item): void;

    public function removePackageItem(OrderPackageItemInterface $item): void;

    public function hasPackageItem(OrderPackageItemInterface $item): bool;
}
