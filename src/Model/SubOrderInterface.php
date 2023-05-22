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

use CoreShop\Component\Core\Model\OrderInterface;

interface SubOrderInterface extends OrderInterface
{
    /**
     * @return OrderPackageInterface[]
     */
    public function getPackages(): ?array;

    public function setPackages(array $packages);

    public function hasPackages(): bool;

    public function addPackage(OrderPackageInterface $package): void;

    public function removePackage(OrderPackageInterface $package): void;

    public function hasPackage(OrderPackageInterface $package): bool;

    public function getOrder(): ?\CoreShop\Component\Order\Model\OrderInterface;

    public function setOrder(?\CoreShop\Component\Order\Model\OrderInterface $order);

    public function getIsSuborder(): ?bool;

    public function setIsSuborder(?bool $isSuborder);

    public function setShipping(int $shipping, bool $withTax = false);

    public function getShippingNet(): int;

    public function setShippingNet(int $shippingNet);

    public function getShippingGross(): int;

    public function setShippingGross(int $shippingGross);
}
