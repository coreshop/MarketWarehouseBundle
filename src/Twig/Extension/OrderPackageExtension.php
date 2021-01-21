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

namespace CoreShop\Bundle\MarketWarehouseBundle\Twig\Extension;

use CoreShop\Bundle\MarketWarehouseBundle\Repository\OrderPackageRepositoryInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class OrderPackageExtension extends AbstractExtension
{
    protected $orderPackageRepository;

    public function __construct(OrderPackageRepositoryInterface $orderPackageRepository)
    {
        $this->orderPackageRepository = $orderPackageRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('coreshop_market_warehouse_order_packages', [$this, 'orderPackages']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('coreshop_market_warehouse_order_packages', [$this, 'orderPackages']),
        ];
    }

    public function orderPackages(OrderInterface $cart)
    {
        return $this->orderPackageRepository->findForOrder($cart);
    }
}

