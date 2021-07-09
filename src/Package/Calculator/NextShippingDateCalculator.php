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
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;

class NextShippingDateCalculator implements NextShippingDateCalculatorInterface
{
    protected ShippingDateValidatorInterface $shippingDateValidator;

    public function __construct(ShippingDateValidatorInterface $shippingDateValidator)
    {
        $this->shippingDateValidator = $shippingDateValidator;
    }

    public function calculateNextAvailableShippingDate(OrderPackageInterface $package, int $days): ?Carbon
    {
        $warehouse = $package->getWarehouse();

        if (null === $warehouse) {
            return null;
        }

        $today = new Carbon();
        $today->setTime(0, 0);
        $day = $today->addDays($days);

        while (!$this->shippingDateValidator->isShippingDateValid($package, $day)) {
            $day->addDay();
        }

        return $day;
    }
}
