<?php

declare(strict_types=1);

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

namespace CoreShop\Bundle\MarketWarehouseBundle\Controller;

use CoreShop\Bundle\ResourceBundle\Controller\PimcoreController;
use Symfony\Component\HttpFoundation\Request;

class SubOrderController extends PimcoreController
{
    public function getSubOrdersForOrder(Request $request)
    {
        $this->isGrantedOr403();

        $data = [];
        $orderId = (int)$request->get('id');
        if ($orderId) {
            $data = $this->repository->findBy(['order__id' => $orderId]);
        }

        return $this->viewHandler->handle(['success' => true, 'data' => $data]);
    }

    protected function getPermission(): string
    {
        return 'coreshop_permission_order_detail';
    }
}
