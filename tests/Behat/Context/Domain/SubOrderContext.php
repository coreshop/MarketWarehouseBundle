<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 CORS GmbH (https://cors.gmbh)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Domain;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\SubOrderRepositoryInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use Webmozart\Assert\Assert;

final class SubOrderContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private SubOrderRepositoryInterface $subOrderRepository
    ) {

    }

    /**
     * @Then /^there should be one suborder for (my order)$/
     */
    public function thereShouldBeOnePackageForMyCart(OrderInterface $order)
    {
        $subOrders = $this->subOrderRepository->findForOrder($order);

        Assert::eq(
            count($subOrders),
            1,
            sprintf(
                'There should be only one suborder for my order, but found %d',
                count($subOrders)
            )
        );
    }

    /**
     * @Then /^there should be two suborders for (my order)$/
     */
    public function thereShouldBeTwoPackageForMyCart(OrderInterface $order)
    {
        $subOrders = $this->subOrderRepository->findForOrder($order);

        Assert::eq(
            count($subOrders),
            2,
            sprintf(
                'There should be two suborders for my order, but found %d',
                count($subOrders)
            )
        );
    }

    /**
     * @Then /^the subtotal for suborder (\d+) from (my order) is "([^"]+)" including tax$/
     */
    public function theSubotalForSuborderFromMyOrderIsIncludingTax(int $subOrderIndex, OrderInterface $order, int $total)
    {
        $this->checkSubtotalForSubOrder($subOrderIndex, $order, $total, true);
    }

    /**
     * @Then /^the subtotal for suborder (\d+) from (my order) is "([^"]+)" excluding tax$/
     */
    public function theSubotalForSuborderFromMyOrderIsExcludingTax(
        int $subOrderIndex,
        OrderInterface $order,
        int $total
    ) {
        $this->checkSubtotalForSubOrder($subOrderIndex, $order, $total, false);
    }

    /**
     * @Then /^the shipping for suborder (\d+) from (my order) is "([^"]+)" including tax$/
     */
    public function theShippingForSuborderFromMyOrderIsIncludingTax(
        int $subOrderIndex,
        OrderInterface $order,
        int $total
    ) {
        $this->checkShippingForSubOrder($subOrderIndex, $order, $total, true);
    }

    /**
     * @Then /^the shipping for suborder (\d+) from (my order) is "([^"]+)" excluding tax$/
     */
    public function theShippingForSuborderFromMyOrderIsExcludingTax(
        int $subOrderIndex,
        OrderInterface $order,
        int $total
    ) {
        $this->checkShippingForSubOrder($subOrderIndex, $order, $total, false);
    }

    /**
     * @Then /^the suborder (\d+) from (my order) has (\d+) suborder items$/
     */
    public function theSubOrderFromMyOrderHasXSubOrderItems(int $subOrderIndex, OrderInterface $order, int $count = 2)
    {
        $subOrders = $this->subOrderRepository->findForOrder($order);

        Assert::minCount($subOrders, $subOrderIndex);

        $subOrderIndex--;

        /**
         * @var SubOrderInterface[] $subOrders
         */
        Assert::eq(
            count($subOrders[$subOrderIndex]->getItems()),
            $count,
            sprintf(
                'Suborder is expected to has %d suborder items but has %d suborder items',
                $count,
                count($subOrders[$subOrderIndex]->getItems())
            )
        );
    }

    protected function checkSubtotalForSubOrder(
        int $subOrderIndex,
        OrderInterface $order,
        int $total,
        bool $withTax = true
    ) {
        $subOrders = $this->subOrderRepository->findForOrder($order);

        Assert::minCount($subOrders, $subOrderIndex);

        $subOrderIndex--;

        /**
         * @var SubOrderInterface[] $subOrders
         */
        Assert::eq(
            $subOrders[$subOrderIndex]->getSubtotal($withTax),
            $total,
            sprintf(
                'Subtotal is expected to be %s but is %s',
                $total,
                $subOrders[$subOrderIndex]->getSubtotal($withTax)
            )
        );
    }

    protected function checkShippingForSubOrder(
        int $subOrderIndex,
        OrderInterface $order,
        int $total,
        bool $withTax = true
    ) {
        $subOrders = $this->subOrderRepository->findForOrder($order);

        Assert::minCount($subOrders, $subOrderIndex);

        $subOrderIndex--;

        /**
         * @var SubOrderInterface[] $subOrders
         */
        Assert::eq(
            $subOrders[$subOrderIndex]->getShipping($withTax),
            $total,
            sprintf(
                'Shipping is expected to be %s but is %s',
                $total,
                $subOrders[$subOrderIndex]->getShipping($withTax)
            )
        );
    }
}
