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

interface BlockedDateInterface extends PimcoreModelInterface
{
    public function getName(): ?string;

    public function getDay(): ?int;

    public function setDay(?int $day);

    public function getMonth(): ?int;

    public function setMonth(?int $month);

    public function getYear(): ?int;

    public function setYear(?int $year);
}
