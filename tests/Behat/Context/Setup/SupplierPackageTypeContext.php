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

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Setup;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\PackageTypeInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\ProductWarehouseStockInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Component\Core\Model\CurrencyInterface;
use CoreShop\Component\Core\Model\ProductInterface;
use CoreShop\Component\Currency\Model\Money;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use Pimcore\Model\DataObject\Service;

final class SupplierPackageTypeContext implements Context
{
    private $sharedStorage;
    private $factory;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $factory
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->factory = $factory;
    }

    /**
     * @Given /^the (supplier) has a package-type with identifier "[^"]+"$/
     */
    public function thereIsAPackageType(SupplierInterface $supplier, string $identifier)
    {
        /**
         * @var PackageTypeInterface $packageType
         */
        $packageType = $this->factory->createNew();

        $packageType->setIdentifier($identifier);
        $packageType->setName($identifier);
        $packageType->setParent(Service::createFolderByPath($supplier->getFullPath().'/package-types'));
        $packageType->setKey(Service::getValidKey($identifier, 'object'));
        $packageType->save();

        $this->sharedStorage->set('package-type', $packageType);
    }

    /**
     * @Given /^the (package-type) has packaging costs of (\d+) for (currency)$/
     * @Given /^the (package-type) has packaging costs of (\d+) for (currency "[^"]+")$/
     */
    public function packageTypeHasPackagingCosts(PackageTypeInterface $packageType, int $price, CurrencyInterface $currency)
    {
        $packageType->setPackagingCosts(new Money($price, $currency));
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) has commission time of (\d+)$/
     */
    public function packageTypeHasCommissionTime(PackageTypeInterface $packageType, int $comissionTime)
    {
        $packageType->setCommissionTime($comissionTime);
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) has packing days of (\d+)$/
     */
    public function packageTypeHasPackingDays(PackageTypeInterface $packageType, float $packingDays)
    {
        $packageType->setPackingDays($packingDays);
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) has pickup days of (\d+)$/
     */
    public function packageTypeHasPickupDays(PackageTypeInterface $packageType, float $pickupDays)
    {
        $packageType->setPickUpDays($pickupDays);
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) has carriage time of (\d+)$/
     */
    public function packageTypeHasCarriageTime(PackageTypeInterface $packageType, float $carriageTime)
    {
        $packageType->setCarriageTime($carriageTime);
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) is single-delivery only$/
     */
    public function packageTypeIsSingleDelivery(PackageTypeInterface $packageType)
    {
        $packageType->setSingleDeliveryOnline(true);
        $packageType->save();
    }

    /**
     * @Given /^the (package-type) is not single-delivery only$/
     */
    public function packageTypeIsNotSingleDelivery(PackageTypeInterface $packageType)
    {
        $packageType->setSingleDeliveryOnline(false);
        $packageType->save();
    }
}
