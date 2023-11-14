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
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\PackagerInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\OrderPackageRepositoryInterface;
use CoreShop\Component\Order\Factory\AdjustmentFactoryInterface;
use CoreShop\Component\Order\Model\AdjustmentInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;
use Pimcore\Model\DataObject\Service;
use Webmozart\Assert\Assert;

class OrderPackageProcessor implements CartProcessorInterface
{
    protected $orderPackageRepository;
    protected $packager;
    protected $orderPackageProcessor;
    protected $adjustmentFactory;

    public function __construct(
        OrderPackageRepositoryInterface $orderPackageRepository,
        PackagerInterface $packager,
        OrderPackageProcessorInterface $orderPackageProcessor,
        AdjustmentFactoryInterface $adjustmentFactory
    )
    {
        $this->orderPackageRepository = $orderPackageRepository;
        $this->packager = $packager;
        $this->orderPackageProcessor = $orderPackageProcessor;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    public function process(OrderInterface $cart): void
    {
        if (!$cart->getId()) {
            return;
        }

        if ($cart->getImmutable()) {
            return;
        }

        if ($cart instanceof SubOrderInterface && $cart->getIsSuborder()) {
            //Suborder gets the package set from the SubOrderCreator, no need to calculate it
            return;
        }

        /**
         * @var SubOrderInterface $cart
         */
        Assert::isInstanceOf($cart, SubOrderInterface::class);

        $existingPackages = $cart->getPackages();
        $packages = $this->packager->createOrderPackages($cart, $existingPackages);

        foreach ($existingPackages as $existingPackage) {
            $found = false;
            foreach ($packages as $package) {
                if ($package->getId() === $existingPackage->getId()) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $existingPackage->delete();
            }
        }

        $shippingNet = 0;
        $shippingGross = 0;
        $keepOriginalShipping = false;

        foreach ($packages as $index => $package) {
            $this->orderPackageProcessor->process($package);

            if ($package->getWarehouse() === null) {
                $keepOriginalShipping = true;
            }

            $items = $package->getItems();

            $package->setItems([]);

            $package->setParent(Service::createFolderByPath(sprintf('%s/packages', $cart->getFullPath())));
            $package->setKey($index);
            $package->setPublished(true);
            $package->save();

            foreach ($items as $packageIndex => $item) {
                $item->setParent(Service::createFolderByPath(sprintf('%s/items', $package->getFullPath())));
                $item->setKey($packageIndex);
                $item->setPublished(true);
                $item->save();
            }

            $package->setItems($items);
            $package->save();

            $shippingNet += $package->getShippingNet();
            $shippingGross += $package->getShippingGross();
        }

        $cart->removeAdjustmentsRecursively('shipping_original');

        if ($keepOriginalShipping) {
            foreach ($cart->getAdjustments(AdjustmentInterface::SHIPPING) as $adjustment) {
                $cart->addAdjustment(
                    $this->adjustmentFactory->createWithData(
                        'shipping_original',
                        'Shipping Rest of the Cart',
                        $adjustment->getAmount(true),
                        $adjustment->getAmount(false),
                        true
                    )
                );
            }
        }
        else {
            $cart->removeAdjustmentsRecursively(AdjustmentInterface::SHIPPING);
        }

        $cart->addAdjustment(
            $this->adjustmentFactory->createWithData(
                AdjustmentInterface::SHIPPING,
                'Packages',
                $shippingGross,
                $shippingNet,
                false
            )
        );

        $cart->setPackages($packages);
    }
}
