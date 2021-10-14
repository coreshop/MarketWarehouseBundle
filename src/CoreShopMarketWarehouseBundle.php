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

use CoreShop\Bundle\CoreBundle\CoreShopCoreBundle;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\RegisterOrderPackageProcessorPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\SupplierShippingRuleActionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\SupplierShippingRuleConditionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\WarehouseDeliveryTimeRuleActionPass;
use CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler\WarehouseDeliveryTimeRuleConditionPass;
use CoreShop\Bundle\ResourceBundle\AbstractResourceBundle;
use CoreShop\Bundle\ResourceBundle\CoreShopResourceBundle;
use CoreShop\Bundle\ResourceBundle\ResourceBundleInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Pimcore\Extension\Bundle\PimcoreBundleInterface;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;
use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CoreShopMarketWarehouseBundle extends AbstractResourceBundle implements PimcoreBundleInterface
{
    use PackageVersionTrait { getVersion as getVersionTrait; }

    public function getSupportedDrivers(): array
    {
        return [
            CoreShopResourceBundle::DRIVER_DOCTRINE_ORM,
        ];
    }

    public function getVersion(): string
    {
        return $this->getVersionTrait();
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

    protected function getComposerPackageName(): string
    {
        return 'coreshop/market-warehouse-bundle';
    }

    public function getNiceName()
    {
        return 'Market Warehouse Bundle';
    }

    public function getInstaller()
    {
        return $this->container->get(Installer::class);
    }

    public function getAdminIframePath()
    {
        return null;
    }

    public function getJsPaths()
    {
        return [];
    }

    public function getCssPaths()
    {
        return [];
    }

    public function getEditmodeJsPaths()
    {
        return [];
    }

    public function getEditmodeCssPaths()
    {
        return [];
    }
}
