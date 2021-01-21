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

use CoreShop\Component\Resource\Model\TranslationInterface;
use CoreShop\Component\Resource\Pimcore\Model\AbstractPimcoreModel;
use CoreShop\Component\Shipping\Model\ShippingRuleGroupInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use Doctrine\Common\Collections\Collection;

abstract class SupplierCarrier extends AbstractPimcoreModel implements SupplierCarrierInterface
{
    public function getTaxCalculationStrategy()
    {
        return 'taxRule';
    }

    public function setTaxCalculationStrategy($taxCalculationStrategy)
    {

    }

    public function getStores()
    {
        throw new \Exception('Not implemented');
    }

    public function hasStore(StoreInterface $store)
    {
        throw new \Exception('Not implemented');
    }

    public function addStore(StoreInterface $store)
    {
        throw new \Exception('Not implemented');
    }

    public function removeStore(StoreInterface $store)
    {
        throw new \Exception('Not implemented');
    }

    public function getTranslations()
    {
        throw new \Exception('Not implemented');
    }

    public function getTranslation($locale = null)
    {
        throw new \Exception('Not implemented');
    }

    public function hasTranslation(TranslationInterface $translation)
    {
        throw new \Exception('Not implemented');
    }

    public function addTranslation(TranslationInterface $translation)
    {
        throw new \Exception('Not implemented');
    }

    public function removeTranslation(TranslationInterface $translation)
    {
        throw new \Exception('Not implemented');
    }

    public function setCurrentLocale($locale)
    {
        throw new \Exception('Not implemented');
    }

    public function setFallbackLocale($locale)
    {
        throw new \Exception('Not implemented');
    }

    public function getIsFree()
    {
        throw new \Exception('Not implemented');
    }

    public function setIsFree($isFree)
    {
        throw new \Exception('Not implemented');
    }

    public function hasShippingRules()
    {
        return true;
    }

    public function addShippingRule(ShippingRuleGroupInterface $shippingRuleGroup)
    {
        throw new \Exception('Not implemented');
    }

    public function removeShippingRule(ShippingRuleGroupInterface $shippingRuleGroup)
    {
        throw new \Exception('Not implemented');
    }

    public function hasShippingRule(ShippingRuleGroupInterface $shippingRuleGroup)
    {
        throw new \Exception('Not implemented');
    }
}
