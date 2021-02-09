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

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\WarehouseDeliveryTimeProcessorInterface;
use CoreShop\Component\Core\Provider\AddressProviderInterface;

class DeliveryTimeCalculator implements DeliveryTimeCalculatorInterface
{
    private $warehouseDeliveryTimeProcessor;
    private $defaultAddressProvider;

    public function __construct(
        WarehouseDeliveryTimeProcessorInterface $warehouseDeliveryTimeProcessor,
        AddressProviderInterface $defaultAddressProvider
    )
    {
        $this->warehouseDeliveryTimeProcessor = $warehouseDeliveryTimeProcessor;
        $this->defaultAddressProvider = $defaultAddressProvider;
    }

    public function calculateDeliveryTime(OrderPackageInterface $package, array $context)
    {
        if (!$package->getAddress()) {
            return;
        }

        $deliveryTime = $this->warehouseDeliveryTimeProcessor->calculateDeliveryTime(
            $package->getWarehouse(),
            $package->getOrder(),
            $package->getAddress() ?? $this->defaultAddressProvider->getAddress($package->getOrder()),
            $context
        );
        $package->setShippingTime($deliveryTime->getDays());
    }
}
