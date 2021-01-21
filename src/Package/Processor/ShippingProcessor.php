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
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Core\Model\CarrierInterface;
use CoreShop\Component\Core\Provider\AddressProviderInterface;
use CoreShop\Component\Shipping\Calculator\TaxedShippingCalculatorInterface;
use CoreShop\Component\Shipping\Exception\UnresolvedDefaultCarrierException;
use CoreShop\Component\Shipping\Resolver\CarriersResolverInterface;
use CoreShop\Component\Shipping\Validator\ShippableCarrierValidatorInterface;

class ShippingProcessor implements OrderPackageProcessorInterface
{
    private $carrierPriceCalculator;
    private $carrierValidator;
    private $defaultCarrierResolver;
    private $defaultAddressProvider;

    public function __construct(
        TaxedShippingCalculatorInterface $carrierPriceCalculator,
        ShippableCarrierValidatorInterface $carrierValidator,
        CarriersResolverInterface $defaultCarrierResolver,
        AddressProviderInterface $defaultAddressProvider
    ) {
        $this->carrierPriceCalculator = $carrierPriceCalculator;
        $this->carrierValidator = $carrierValidator;
        $this->defaultCarrierResolver = $defaultCarrierResolver;
        $this->defaultAddressProvider = $defaultAddressProvider;
    }

    public function process(OrderPackageInterface $package): void
    {
        $package->setShippingGross(0);
        $package->setShippingNet(0);

        $address = $package->getOrder()->getShippingAddress() ?: $this->defaultAddressProvider->getAddress($package->getOrder());

        if (null === $address) {
            return;
        }

        if ($package->getCarrier() instanceof CarrierInterface &&
            !$this->carrierValidator->isCarrierValid($package->getCarrier(), $package, $address)
        ) {
            $package->setCarrier(null);
        }

        if (null === $package->getCarrier()) {
            $carrier = $this->resolveDefaultCarrier($package, $address);

            if (null === $carrier) {
                return;
            }

            $package->setCarrier($carrier);
        }

        $priceWithTax = $this->carrierPriceCalculator->getPrice($package->getCarrier(), $package, $address, true);
        $priceWithoutTax = $this->carrierPriceCalculator->getPrice($package->getCarrier(), $package, $address, false);

        $package->setShippingGross($priceWithTax);
        $package->setShippingNet($priceWithoutTax);
    }

    private function resolveDefaultCarrier(OrderPackageInterface $package, AddressInterface $address)
    {
        try {
            return $this->defaultCarrierResolver->getDefaultCarrier($package, $address);
        } catch (UnresolvedDefaultCarrierException $ex) {
        }

        return null;
    }
}
