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

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Domain;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\OrderPackageRepositoryInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Order\Context\CartContextInterface;
use Webmozart\Assert\Assert;

final class OrderPackageContext implements Context
{
    private $sharedStorage;
    private $cartContext;
    private $orderPackageRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        CartContextInterface $cartContext,
        OrderPackageRepositoryInterface $orderPackageRepository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->cartContext = $cartContext;
        $this->orderPackageRepository = $orderPackageRepository;
    }

    /**
     * @Then /^there should be one package for my cart$/
     */
    public function thereShouldBeOnePackageForMyCart()
    {
        $order = $this->cartContext->getCart();
        $packages = $this->orderPackageRepository->findForOrder($order);

        Assert::eq(
            count($packages),
            1,
            sprintf(
                'There should be only one package for the cart, but found %d',
                count($packages)
            )
        );
    }

    /**
     * @Then /^there should be (\d+) packages for my cart$/
     */
    public function thereShouldBeXPackageForMyCart(int $count = 2)
    {
        $order = $this->cartContext->getCart();
        $packages = $this->orderPackageRepository->findForOrder($order);

        Assert::eq(
            count($packages),
            $count,
            sprintf(
                'There should be only one package for the cart, but found %d',
                count($packages)
            )
        );
    }

    /**
     * @Then /^the package (\d+) from (my order) has (\d+) package items$/
     */
    public function thePackageFromMyOrderHasXPackageItems(int $packageIndex, OrderInterface $order, int $count = 2)
    {
        $packages = $this->orderPackageRepository->findForOrder($order);

        Assert::minCount($packages, $packageIndex);

        $packageIndex--;

        /**
         * @var OrderPackageInterface[] $packages
         */
        Assert::eq(
            count($packages[$packageIndex]->getItems()),
            $count,
            sprintf(
                'Package is expected to has %d package items but has %d package items',
                $count,
                count($packages[$packageIndex]->getItems())
            )
        );
    }
}
