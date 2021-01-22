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

namespace CoreShop\Bundle\MarketWarehouseBundle\Model;

use CoreShop\Component\Order\Model\PurchasableInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

interface ProductWarehouseStockInterface extends PimcoreModelInterface
{
    public function getProduct(): ?PurchasableInterface;

    public function setProduct(?PurchasableInterface $product);

    public function getPackageType(): ?PackageTypeInterface;

    public function setPackageType(?PackageTypeInterface $packageType);

    public function getWarehouse(): ?WarehouseInterface;

    public function setWarehouse(?WarehouseInterface $warehouse);

    public function getStock(): ?float;

    public function setStock(?float $stock);
}
