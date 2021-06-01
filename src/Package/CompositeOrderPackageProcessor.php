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

namespace CoreShop\Bundle\MarketWarehouseBundle\Package;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use Laminas\Stdlib\PriorityQueue;

final class CompositeOrderPackageProcessor implements OrderPackageProcessorInterface
{
    private PriorityQueue $processors;

    public function __construct()
    {
        $this->processors = new PriorityQueue();
    }

    public function addProcessor(OrderPackageProcessorInterface $processor, $priority = 0): void
    {
        $this->processors->insert($processor, $priority);
    }

    public function process(OrderPackageInterface $package): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($package);
        }
    }
}
