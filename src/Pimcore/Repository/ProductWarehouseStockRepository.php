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

namespace CoreShop\Bundle\MarketWarehouseBundle\Pimcore\Repository;

use CoreShop\Bundle\MarketWarehouseBundle\Repository\ProductWarehouseStockRepositoryInterface;
use CoreShop\Bundle\ResourceBundle\Pimcore\PimcoreRepository;
use CoreShop\Component\Product\Model\ProductInterface;

class ProductWarehouseStockRepository extends PimcoreRepository implements ProductWarehouseStockRepositoryInterface
{
    public function findForProduct(ProductInterface $product): array
    {
        $list = $this->getList();
        $list->setCondition('product__id = ?', $product->getId());

        return $list->getObjects();
    }
}
