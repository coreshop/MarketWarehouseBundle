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

namespace CoreShop\Bundle\MarketWarehouseBundle\Package\Shipping;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Shipping\Model\CarrierInterface;
use CoreShop\Component\Shipping\Model\ShippableInterface;
use CoreShop\Component\Shipping\Resolver\CarriersResolverInterface;
use CoreShop\Component\Shipping\Validator\ShippableCarrierValidatorInterface;

class PackageCarrierValidator implements ShippableCarrierValidatorInterface
{
    protected ShippableCarrierValidatorInterface $decorated;

    public function __construct(ShippableCarrierValidatorInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function isCarrierValid(CarrierInterface $carrier, ShippableInterface $shippable, AddressInterface $address): bool
    {
        if (!$shippable instanceof OrderPackageInterface) {
            return $this->decorated->isCarrierValid($carrier, $shippable, $address);
        }

        $warehouse = $shippable->getWarehouse();

        if (!$warehouse) {
            return false;
        }

        $supplier = $warehouse->getSupplier();

        if (!$supplier) {
            return false;
        }

        $carriers = $supplier->getCarriers();
        $found = false;

        foreach ($carriers as $supplierCarrier) {
            if ($carrier->getId() === $supplierCarrier->getId()) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            return false;
        }

        return $this->decorated->isCarrierValid($carrier, $shippable, $address);
    }
}
