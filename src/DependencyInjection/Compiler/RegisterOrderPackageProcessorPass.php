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

namespace CoreShop\Bundle\MarketWarehouseBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\MarketWarehouseBundle\Package\CompositeOrderPackageProcessor;
use CoreShop\Bundle\MarketWarehouseBundle\Package\OrderPackageProcessorInterface;
use CoreShop\Component\Registry\PrioritizedCompositeServicePass;

final class RegisterOrderPackageProcessorPass extends PrioritizedCompositeServicePass
{
    public const ORDER_PACKAGE_PROCESSOR_TAG = 'coreshop.order.package_processor';

    public function __construct()
    {
        parent::__construct(
            OrderPackageProcessorInterface::class,
            CompositeOrderPackageProcessor::class,
            self::ORDER_PACKAGE_PROCESSOR_TAG,
            'addProcessor'
        );
    }
}
