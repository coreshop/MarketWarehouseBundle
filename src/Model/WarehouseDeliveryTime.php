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

class WarehouseDeliveryTime implements WarehouseDeliveryTimeInterface
{
    protected $days = 0;
    protected $isValid = true;

    public function getDays(): int
    {
        return $this->days;
    }

    public function addDays(int $days): void
    {
        $this->days += $days;
    }

    public function removeDays(int $days): void
    {
        $this->days -= $days;
    }

    public function invalid(): void
    {
        $this->isValid = false;
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }
}
