<?php

use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Pimcore\Kernel as PimcoreKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends PimcoreKernel
{
    public function registerBundlesToCollection(BundleCollection $collection)
    {
        parent::registerBundlesToCollection($collection);

        $collection->addBundle(new \CoreShop\Bundle\CoreBundle\CoreShopCoreBundle());
        $collection->addBundle(new \CoreShop\Bundle\MarketWarehouseBundle\CoreShopMarketWarehouseBundle());
        $collection->addBundle(new \FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle());
        $collection->addBundle(new \CoreShop\Bundle\TestBundle\CoreShopTestBundle(), 0);
        $collection->addBundle(new \CoreShop\Bundle\ClassDefinitionPatchBundle\CoreShopClassDefinitionPatchBundle(), 0);
    }
}
