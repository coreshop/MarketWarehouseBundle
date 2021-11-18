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

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Setup;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Context\Setup\ActionFormTrait;
use CoreShop\Behat\Context\Setup\ConditionFormTrait;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Bundle\CoreBundle\Form\Type\Shipping\Rule\Action\PriceActionConfigurationType;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierCarrierInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierShippingRuleInterface;
use CoreShop\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use CoreShop\Bundle\ShippingBundle\Form\Type\ShippingRuleActionType;
use CoreShop\Bundle\ShippingBundle\Form\Type\ShippingRuleConditionType;
use CoreShop\Component\Core\Model\CurrencyInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use CoreShop\Component\Rule\Model\ActionInterface;
use CoreShop\Component\Shipping\Model\ShippingRuleInterface;
use CoreShop\Component\Taxation\Model\TaxRuleGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Pimcore\File;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Tool;
use Symfony\Component\Form\FormFactoryInterface;

final class SupplierShippingContext implements Context
{
    use ConditionFormTrait;

    use ActionFormTrait;

    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private ObjectManager $objectManager,
        private FormFactoryInterface $formFactory,
        private FormTypeRegistryInterface $conditionFormTypeRegistry,
        private FormTypeRegistryInterface $actionFormTypeRegistry,
        private FactoryInterface $supplierCarrierFactory,
        private FactoryInterface $shippingRuleFactory
    ) {
    }

    /**
     * @Given /^the (supplier-carrier "[^"]+") has (tax rule group "[^"]+")$/
     */
    public function theSupplierCarrierHasTaxRuleGroup(SupplierCarrierInterface $supplierCarrier, TaxRuleGroupInterface $taxRule): void
    {
        $supplierCarrier->setTaxRule($taxRule);
        $supplierCarrier->save();
    }

    /**
     * @Given /^the site has a supplier-carrier "([^"]+)"$/
     * @Given /^the site has another supplier-carrier "([^"]+)"$/
     */
    public function theSiteHasASupplierCarrier(string $name): void
    {
        $supplierCarrier = $this->createSupplierCarrier($name);
        $supplierCarrier->save();

        $this->sharedStorage->set('supplier-carrier', $supplierCarrier);
    }

    /**
     * @Given /^the site has a supplier-carrier "([^"]+)" and ships for (\d+) in (currency "[^"]+") for (supplier "([^"]+)")$/
     */
    public function theSiteHasASupplierCarrierAndShipsForXInCurrencyForSupplier(
        string $name,
        int $price,
        CurrencyInterface $currency,
        SupplierInterface $supplier
    ): void {
        $supplierCarrier = $this->createSupplierCarrier($name);
        $supplier->setCarriers([$supplierCarrier]);
        $supplierCarrier->save();

        /**
         * @var SupplierShippingRuleInterface $rule
         */
        $rule = $this->shippingRuleFactory->createNew();
        $rule->setName($name);
        $rule->setActive(true);

        $this->assertActionForm(PriceActionConfigurationType::class, 'price');

        $this->addAction($rule, $this->createActionWithForm('price', [
            'price' => $price,
            'currency' => $currency->getId(),
        ]));

        $supplierCarrier->setShippingRules([$rule]);
        $supplierCarrier->save();

        $this->sharedStorage->set('supplier-carrier', $supplierCarrier);
        $this->sharedStorage->set('shipping-rule', $rule);
    }

    private function createSupplierCarrier(
        string $name,
        TaxRuleGroupInterface $taxRule = null
    ): SupplierCarrierInterface {
        /** @var SupplierCarrierInterface $supplierCarrier */
        $supplierCarrier = $this->supplierCarrierFactory->createNew();
        $supplierCarrier->setIdentifier($name);
        $supplierCarrier->setKey(File::getValidFilename($name));
        $supplierCarrier->setParent(Folder::getByPath('/'));

        if ($taxRule) {
            $supplierCarrier->setTaxRule($taxRule);
        }

        foreach (Tool::getValidLanguages() as $lang) {
            $supplierCarrier->setTitle($name, $lang);
        }

        return $supplierCarrier;
    }

    private function addAction(ShippingRuleInterface $rule, ActionInterface $action): void
    {
        $rule->addAction($action);
    }

    protected function getConditionFormClass(): string
    {
        return ShippingRuleConditionType::class;
    }


    protected function getActionFormRegistry(): FormTypeRegistryInterface
    {
        return $this->actionFormTypeRegistry;
    }

    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    protected function getActionFormClass(): string
    {
        return ShippingRuleActionType::class;
    }

    protected function getConditionFormRegistry(): FormTypeRegistryInterface
    {
        return $this->conditionFormTypeRegistry;
    }
}
