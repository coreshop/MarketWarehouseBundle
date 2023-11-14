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

namespace CoreShop\Bundle\MarketWarehouseBundle\Package\Processor;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\Calculator\DeliveryTimeCalculatorInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\Calculator\NextShippingDateCalculatorInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;

class DeliveryTimeProcessor implements OrderPackageProcessorInterface
{
    protected DeliveryTimeCalculatorInterface $deliveryTimeCalculator;
    protected CartContextResolverInterface $cartContextResolver;
    protected NextShippingDateCalculatorInterface $nextShippingDateCalculator;

    public function __construct(
        DeliveryTimeCalculatorInterface $deliveryTimeCalculator,
        CartContextResolverInterface $cartContextResolver,
        NextShippingDateCalculatorInterface $nextShippingDateCalculator
    ) {
        $this->deliveryTimeCalculator = $deliveryTimeCalculator;
        $this->cartContextResolver = $cartContextResolver;
        $this->nextShippingDateCalculator = $nextShippingDateCalculator;
    }

    public function process(OrderPackageInterface $package): void
    {
        if ($package->isImmutable()) {
            return;
        }

        $deliveryTime = $this->deliveryTimeCalculator->calculateDeliveryTime(
            $package,
            $this->cartContextResolver->resolveCartContext($package->getOrder())
        );

        $package->setShippingTime($deliveryTime);

        if (null !== $deliveryTime) {
            $package->setShippingDate(
                $this->nextShippingDateCalculator->calculateNextAvailableShippingDate(
                    $package,
                    $deliveryTime
                 )
            );

            if (null === $package->getWishedShippingDate()) {
                $package->setWishedShippingDate($package->getShippingDate());
            }
            elseif ($package->getWishedShippingDate()->isBefore($package->getShippingDate())) {
                $package->setWishedShippingDate($package->getShippingDate());
            }
        }
    }
}
