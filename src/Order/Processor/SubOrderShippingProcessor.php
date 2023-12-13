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

namespace CoreShop\Bundle\MarketWarehouseBundle\Order\Processor;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Component\Order\Factory\AdjustmentFactoryInterface;
use CoreShop\Component\Order\Model\AdjustmentInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;

class SubOrderShippingProcessor implements CartProcessorInterface
{
    public function __construct(
        private AdjustmentFactoryInterface $adjustmentFactory
    ) {
    }

    public function process(OrderInterface $cart): void
    {
        if ($cart->getImmutable()) {
            return;
        }

        if (!$cart instanceof SubOrderInterface) {
            return;
        }

        if (!$cart->getIsSuborder()) {
            return;
        }

        $shippingTotal = 0;
        $shippingTotalNet = 0;

        foreach ($cart->getPackages() as $package) {
            $shippingTotal += $package->getShipping(true);
            $shippingTotalNet += $package->getShipping(false);
        }

        $cart->addAdjustment(
            $this->adjustmentFactory->createWithData(
                AdjustmentInterface::SHIPPING,
                'Package Shipping',
                $shippingTotal,
                $shippingTotalNet
            )
        );
    }
}
