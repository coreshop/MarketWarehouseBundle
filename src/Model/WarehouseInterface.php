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

use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

interface WarehouseInterface extends PimcoreModelInterface
{
    public function getIdentifier(): ?string;

    /**
     * @return WarehouseDeliveryTimeRuleInterface[]
     */
    public function getDeliveryTimeRules(): array;

    public function setDeliveryTimeRules(array $deliveryTimeRules);

    /**
     * @return SupplierInterface
     */
    public function getSupplier(): ?SupplierInterface;

    public function setSupplier(?SupplierInterface $supplier);

    public function getSaturdayEnabled(): ?bool;

    public function setSaturdayEnabled(?bool $saturdayEnabled);

    public function getSundayEnabled(): ?bool;

    public function setSundayEnabled(?bool $sundayEnabled);

    /**
     * @return BlockedDateInterface[]
     */
    public function getBlockedDays(): array;

    public function setBlockedDays(array $blockedDays);
}
