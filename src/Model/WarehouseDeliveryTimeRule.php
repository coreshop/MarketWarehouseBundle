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

use CoreShop\Component\Rule\Model\RuleTrait;

class WarehouseDeliveryTimeRule implements WarehouseDeliveryTimeRuleInterface
{
    use RuleTrait  {
        initializeRuleCollections as private initializeRules;
    }

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var int
     */
    protected $warehouse;

    public function __construct()
    {
        $this->initializeRules();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * @param int $warehouse
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
    }

    public function __clone()
    {
        $this->id = null;
    }
}
