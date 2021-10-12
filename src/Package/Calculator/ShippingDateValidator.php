<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Package\Calculator;

use Carbon\Carbon;
use CoreShop\Bundle\MarketWarehouseBundle\Model\BlockedDateInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;

class ShippingDateValidator implements ShippingDateValidatorInterface
{
    public function isShippingDateValid(OrderPackageInterface $package, Carbon $day): bool
    {
        $warehouse = $package->getWarehouse();
        $earliestShippingDate = $package->getShippingDate();

        if ($day->isPast()) {
            return false;
        }

        if ($day->isBefore($earliestShippingDate)) {
            return false;
        }

        if (null === $warehouse) {
            return true;
        }

        if ($day->isSaturday() && !$warehouse->getSaturdayEnabled()) {
            return false;
        }

        if ($day->isSunday() && !$warehouse->getSundayEnabled()) {
            return false;
        }

        $blockedDates = $warehouse->getBlockedDays();

        usort($blockedDates, static function (BlockedDateInterface $a, BlockedDateInterface $b) {
            $date1 = Carbon::createFromDate($a->getYear(), $a->getMonth(), $a->getDay());
            $date2 = Carbon::createFromDate($b->getYear(), $b->getMonth(), $b->getDay());

            return $date1 < $date2 ? -1 : 1;
        });

        foreach ($blockedDates as $blockedDate) {
            if ($blockedDate->getDay() === $day->day && $blockedDate->getMonth() === $day->month && ($blockedDate->getYear() === null || $blockedDate->getYear() === $day->year)) {
                return false;
            }
        }

        return true;
    }
}
