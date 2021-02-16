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

namespace CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\Action;

use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTimeInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Model\OrderInterface;

class DaysActionProcessor implements WarehouseDeliveryTimeActionProcessorInterface
{
    public function calculateDeliveryTime(
        WarehouseInterface $subject,
        WarehouseDeliveryTimeInterface $deliveryTime,
        OrderInterface $order,
        AddressInterface $address,
        array $configuration,
        array $context
    ): void {
        $deliveryTime->addDays((int)$configuration['days']);
    }
}
