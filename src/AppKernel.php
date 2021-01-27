<?php
/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

namespace App;

use Pimcore\HttpKernel\BundleCollection\BundleCollection;
use Pimcore\Kernel as PimcoreKernel;

class Kernel extends \Kernel
{
    public function registerBundlesToCollection(BundleCollection $collection)
    {
        $collection->addBundle(new \CoreShop\Bundle\CoreBundle\CoreShopCoreBundle());
        $collection->addBundle(new \CoreShop\Bundle\MarketWarehouseBundle\CoreShopMarketWarehouseBundle());
        $collection->addBundle(new \FriendsOfBehat\SymfonyExtension\Bundle\FriendsOfBehatSymfonyExtensionBundle());
        $collection->addBundle(new \CoreShop\Bundle\TestBundle\CoreShopTestBundle(), 0);
    }

    public function boot()
    {
        \Pimcore::setKernel($this);

        parent::boot();
    }
}
