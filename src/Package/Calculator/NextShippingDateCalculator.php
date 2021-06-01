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

class NextShippingDateCalculator implements NextShippingDateCalculatorInterface
{
    public function calculateNextAvailableShippingDate(OrderPackageInterface $package, int $days): ?Carbon
    {
        $warehouse = $package->getWarehouse();

        if (null === $warehouse) {
            return null;
        }

        $today = new Carbon();
        $day = $today->addDays($days);

        if ($day->isSaturday() && !$warehouse->getSaturdayEnabled()) {
            $day->addDay();
        }

        if ($day->isSunday() && !$warehouse->getSundayEnabled()) {
            $day->addDay();
        }

        $blockedDates = $warehouse->getBlockedDays();

        usort($blockedDates, static function (BlockedDateInterface $a, BlockedDateInterface $b) {
            $date1 = Carbon::createFromDate($a->getYear(), $a->getMonth(), $a->getDay());
            $date2 = Carbon::createFromDate($b->getYear(), $b->getMonth(), $b->getDay());

            return $date1 < $date2 ? -1 : 1;
        });

        foreach ($blockedDates as $blockedDate) {
            if ($blockedDate->getDay() === $day->day && $blockedDate->getMonth() === $day->month && ($blockedDate->getYear() === null || $blockedDate->getYear() === $day->year)) {
                $day->addDay();

                if ($day->isSaturday() && !$warehouse->getSaturdayEnabled()) {
                    $day->addDay();
                }

                if ($day->isSunday() && !$warehouse->getSundayEnabled()) {
                    $day->addDay();
                }
            }
        }

        return $day;
    }
}
