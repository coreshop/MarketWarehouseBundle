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

use CoreShop\Component\Resource\Model\ImmutableInterface;
use CoreShop\Component\Resource\Model\ImmutableTrait;
use CoreShop\Component\Resource\Pimcore\Model\AbstractPimcoreModel;
use CoreShop\Component\Store\Model\StoreInterface;

abstract class OrderPackage extends AbstractPimcoreModel implements OrderPackageInterface
{
    use ImmutableTrait;

    public function getStore(): ?StoreInterface
    {
        return $this->getOrder() ? $this->getOrder()->getStore() : null;
    }

    public function setStore(?StoreInterface $store)
    {
        throw new \Exception('Not implemented, store comes from the Order');
    }

    public function hasItems(): bool
    {
        return is_array($this->getItems()) && count($this->getItems()) > 0;
    }

    public function addItem(OrderPackageItemInterface $item): void
    {
        $items = $this->getItems();
        $items[] = $item;

        $this->setItems($items);
    }

    public function removeItem(OrderPackageItemInterface $item): void
    {
        $items = $this->getItems();

        for ($i = 0, $c = count($items); $i < $c; $i++) {
            $arrayItem = $items[$i];

            if ($arrayItem->getId() === $item->getId()) {
                unset($items[$i]);

                break;
            }
        }

        $this->setItems(array_values($items));
    }

    public function hasItem(OrderPackageItemInterface $item): bool
    {
        $items = $this->getItems();

        for ($i = 0, $c = count($items); $i < $c; $i++) {
            $arrayItem = $items[$i];

            if ($arrayItem->getId() === $item->getId()) {
                return true;
            }
        }

        return false;
    }

    public function getShipping(bool $withTax = false): int
    {
        return $withTax ? $this->getShippingGross() : $this->getShippingNet();
    }

    public function setShipping(int $shipping, bool $withTax = false)
    {
        $withTax ? $this->setShippingGross($shipping) : $this->setShippingNet($shipping);
    }
    public function getTotal(bool $withTax = false): int
    {
        return 0; //TODO
    }

    public function getSubtotal(bool $withTax = true): int
    {
        return $withTax ? $this->getSubtotalGross() : $this->getSubtotalNet();
    }

    public function setSubtotal(int $subtotal, bool $withTax = false)
    {
        $withTax ? $this->setSubtotalGross($subtotal) : $this->setSubtotalNet($subtotal);
    }
}
