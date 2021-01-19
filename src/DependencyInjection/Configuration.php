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

namespace CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection;

use CoreShop\Bundle\MarketWarehouseBundle\Doctrine\ORM\WarehouseDeliveryTimeRuleRepository;
use CoreShop\Bundle\MarketWarehouseBundle\Form\Type\WarehouseDeliveryTimeRuleType;
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageItemInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\ProductWarehouseStockInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierSaleRuleInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTimeRule;
use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseDeliveryTimeRuleInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Pimcore\Repository\OrderPackageRepository;
use CoreShop\Bundle\MarketWarehouseBundle\Pimcore\Repository\ProductWarehouseStockRepository;
use CoreShop\Bundle\MarketWarehouseBundle\Pimcore\Repository\SupplierSaleRuleRepository;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Component\Resource\Factory\Factory;
use CoreShop\Component\Resource\Factory\PimcoreFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('core_shop_market_warehouse');
        $rootNode = $treeBuilder->getRootNode();

        $this->addModelsSection($rootNode);
        $this->addPimcoreResourcesSection($rootNode);
//        $this->addStack($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addModelsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()

                        ->arrayNode('warehouse_delivery_time_rule')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(WarehouseDeliveryTimeRule::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(WarehouseDeliveryTimeRuleInterface::class)->cannotBeEmpty()->end()
                                        //->scalarNode('admin_controller')->defaultValue(ProductSpecificPriceRuleController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(WarehouseDeliveryTimeRuleRepository::class)->end()
                                        ->scalarNode('form')->defaultValue(WarehouseDeliveryTimeRuleType::class)->cannotBeEmpty()->end()
                                    ->end()
                                ->end()

                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pimcore')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('order_package')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopOrderPackage')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(OrderPackageInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(OrderPackageRepository::class)->end()
//                                        ->scalarNode('install_file')->defaultValue('@CoreShopAddressBundle/Resources/install/pimcore/classes/CoreShopAddress.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('order_package_item')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopOrderPackageItem')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(OrderPackageItemInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->cannotBeEmpty()->end()
//                                        ->scalarNode('install_file')->defaultValue('@CoreShopAddressBundle/Resources/install/pimcore/classes/CoreShopAddress.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('supplier_sale_rule')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopSupplierSaleRule')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(SupplierSaleRuleInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(SupplierSaleRuleRepository::class)->end()
//                                        ->scalarNode('install_file')->defaultValue('@CoreShopAddressBundle/Resources/install/pimcore/classes/CoreShopAddress.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('product_warehouse_stock')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue('Pimcore\Model\DataObject\CoreShopSupplierWarehouseProductStock')->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(ProductWarehouseStockInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(PimcoreFactory::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(ProductWarehouseStockRepository::class)->end()
//                                        ->scalarNode('install_file')->defaultValue('@CoreShopAddressBundle/Resources/install/pimcore/classes/CoreShopAddress.json')->end()
                                        ->scalarNode('type')->defaultValue(CoreShopResourceBundle::PIMCORE_MODEL_TYPE_OBJECT)->cannotBeOverwritten(true)->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addPimcoreResourcesSection(ArrayNodeDefinition $node)
    {
        $node->children()
            ->arrayNode('pimcore_admin')
                ->addDefaultsIfNotSet()
                ->children()
                    ->arrayNode('js')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('css')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('editmode_js')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                    ->arrayNode('editmode_css')
                        ->useAttributeAsKey('name')
                        ->prototype('scalar')->end()
                    ->end()
                    ->scalarNode('permissions')
                        ->cannotBeOverwritten()
                        ->defaultValue([])
                    ->end()
                    ->arrayNode('install')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->arrayNode('admin_translations')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();
    }
}
