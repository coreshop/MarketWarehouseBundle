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

namespace CoreShop\Bundle\MarketWarehouseBundle;

use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\RegisterOrderPackageProcessorPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\SupplierShippingRuleActionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\SupplierShippingRuleConditionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\WarehouseDeliveryTimeRuleActionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\WarehouseDeliveryTimeRuleConditionPass;
use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use Doctrine\Common\Annotations\AnnotationReader;
use Pimcore\Extension\Bundle\Installer\InstallerInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CoreShopMarketWarehouseBundle extends AbstractResourceBundle
{
    public function getSupportedDrivers(): array
    {
        return [
            CoreShopResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        AnnotationReader::addGlobalIgnoredName('alias');

        $container->addCompilerPass(new WarehouseDeliveryTimeRuleActionPass());
        $container->addCompilerPass(new WarehouseDeliveryTimeRuleConditionPass());
        $container->addCompilerPass(new SupplierShippingRuleActionPass());
        $container->addCompilerPass(new SupplierShippingRuleConditionPass());

        $container->addCompilerPass(new RegisterOrderPackageProcessorPass());
    }

    protected function getModelNamespace(): ?string
    {
        return 'CoreShop\Bundle\MarketWarehouseBundle\Model';
    }

    public function getNiceName(): string
    {
        return 'Market Warehouse Bundle';
    }

    public function getPackageName(): string
    {
        return 'coreshop/market-warehouse-bundle';
    }

    public function getInstaller(): ?InstallerInterface
    {
        return $this->container->get(Installer::class);
    }
}
