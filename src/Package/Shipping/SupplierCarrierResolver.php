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
use CoreShop\Component\Shipping\Model\ShippableInterface;
use CoreShop\Component\Shipping\Resolver\CarriersResolverInterface;

class SupplierCarrierResolver implements CarriersResolverInterface
{
    protected $decorated;

    public function __construct(CarriersResolverInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function resolveCarriers(ShippableInterface $shippable, AddressInterface $address): array
    {
        if (!$shippable instanceof OrderPackageInterface) {
            return $this->decorated->resolveCarriers($shippable, $address);
        }

        $warehouse = $shippable->getWarehouse();

        if (!$warehouse) {
            return [];
        }

        $supplier = $warehouse->getSupplier();

        return $supplier->getCarriers();
    }
}
