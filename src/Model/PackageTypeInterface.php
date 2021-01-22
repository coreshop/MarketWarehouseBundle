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

use CoreShop\Component\Currency\Model\Money;
use CoreShop\Component\Resource\Pimcore\Model\PimcoreModelInterface;

interface PackageTypeInterface extends PimcoreModelInterface
{
    public function getIdentifier(): ?string;

    public function setIdentifier(?string $identifier);

    public function getName(): ?string;

    public function setName(?string $name);

    public function getDescription(): ?string;

    public function setDescription(?string $description);

    public function getPackagingCosts(): ?Money;

    public function setPackagingCosts(?Money $packagingCosts);

    public function getCommissionTime(): ?float;

    public function setCommissionTime(?float $commissionTime);

    public function getPackingDays(): ?array;

    public function setPackingDays(?array $packingDays);

    public function getPickUpDays(): ?array;

    public function setPickUpDays(?array $pickupDays);

    public function getSingleDeliveryOnline(): ?bool;

    public function setSingleDeliveryOnline(?bool $singleDeliveryOnline);

    public function getPackStationEnabled(): ?bool;

    public function setPackStationEnabled(?bool $packStationEnabled);

    public function getCutOfTime(): ?string;

    public function setCutOfTime(?string $cutOfTime);

    public function getCutOfTimeDays(): ?float;

    public function setCutOfTimeDays(?float $cutOfTimeDays);

    public function getCarriageTime(): ?float;

    public function setCarriageTime(?float $carriageTime);
}
