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

use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

interface PackageTypeInterface extends PimcoreModelInterface
{
    public function getIdentifier();

    public function setIdentifier($identifier);

    public function getName();

    public function setName($name);

    public function getDescription();

    public function setDescription($description);

    public function getPackagingCosts();

    public function setPackagingCosts($packagingCosts);

    public function getCommissionTime();

    public function setCommissionTime($commissionTime);

    public function getPackingDays();

    public function setPackingDays($packingDays);

    public function getPickUpDays();

    public function setPickUpDays($pickupDays);

    public function getSingleDeliveryOnline();

    public function setSingleDeliveryOnline($singleDeliveryOnline);

    public function getPackStationEnabled();

    public function setPackStationEnabled($packStationEnabled);

    public function getCutOfTime();

    public function setCutOfTime($cutOfTime);

    public function getCutOfTimeDays();

    public function setCutOfTimeDays($cutOfTimeDays);

    public function getCarriageTime();

    public function setCarriageTime($carriageTime);
}
