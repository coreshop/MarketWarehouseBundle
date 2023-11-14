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

namespace CoreShop\Bundle\MarketWarehouseBundle\Order;

use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Model\OrderInterface;

class CartManager implements CartManagerInterface
{
    public function __construct(
        private CartManagerInterface $cartManager,
        private OrderPackageProcessorInterface $orderPackageProcessor,
    )
    {
    }

    public function persistCart(OrderInterface $cart): void
    {
        if (!$cart instanceof SubOrderInterface) {
            $this->cartManager->persistCart($cart);
            return;
        }

        $this->cartManager->persistCart($cart);

        //If we are a suborder, we also need to process the parent cart for changes
        if ($cart->getIsSuborder()) {
            if ($cart->getImmutable()) {
                foreach ($cart->getPackages() as $package) {
                    foreach($package->getItems() as $item) {
                        $item->save();
                    }

                    $package->save();
                }
            }

            $this->cartManager->persistCart($cart->getOrder());
        }
    }
}