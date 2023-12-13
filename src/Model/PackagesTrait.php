<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\Model;

use CoreShop\Component\Resource\Model\ImmutableTrait;

trait PackagesTrait
{
    protected $packages;

    public function getPackages(): ?array
    {
        return $this->packages;
    }
    public function setPackages(array $packages)
    {
        $this->packages = $packages;
    }

    public function hasPackages(): bool
    {
        return is_array($this->getPackages()) && count($this->getPackages()) > 0;
    }

    public function addPackage(OrderPackageInterface $package): void
    {
        $packages = $this->getPackages();
        $packages[] = $package;

        $this->setPackages($packages);
    }

    public function removePackage(OrderPackageInterface $package): void
    {
        $packages = $this->getPackages();

        for ($i = 0, $c = count($packages); $i < $c; $i++) {
            $arrayItem = $packages[$i];

            if ($arrayItem->getId() === $package->getId()) {
                unset($packages[$i]);

                break;
            }
        }

        $this->setPackages(array_values($packages));
    }

    public function hasPackage(OrderPackageInterface $package): bool
    {
        $packages = $this->getPackages();

        for ($i = 0, $c = count($packages); $i < $c; $i++) {
            $arrayItem = $packages[$i];

            if ($arrayItem->getId() === $package->getId()) {
                return true;
            }
        }

        return false;
    }

    public function setShipping(int $shipping, bool $withTax = false)
    {
        $withTax ? $this->setShippingGross($shipping) : $this->setShippingNet($shipping);
    }
}
