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

use CoreShop\Bundle\MarketWarehouseBundle\Package\PackagerInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Repository\OrderPackageRepositoryInterface;
use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;

class OrderPackageProcessor implements CartProcessorInterface
{
    protected $orderPackageRepository;
    protected $packager;

    public function __construct(OrderPackageRepositoryInterface $orderPackageRepository, PackagerInterface $packager)
    {
        $this->orderPackageRepository = $orderPackageRepository;
        $this->packager = $packager;
    }

    public function process(CartInterface $cart)
    {
        if (!$cart->getId()) {
            return;
        }

        foreach ($this->orderPackageRepository->findForOrder($cart) as $package) {
            $package->delete();
        }

        $this->packager->createOrderPackages($cart);
    }
}
