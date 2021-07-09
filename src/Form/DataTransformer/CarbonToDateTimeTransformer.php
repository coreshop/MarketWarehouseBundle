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

namespace CoreShop\Bundle\MarketWarehouseBundle\Form\DataTransformer;

use Carbon\Carbon;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * @author Sander Marechal <s.marechal@jejik.com>
 */
class CarbonToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * {@inheritDoc}
     */
    public function transform($carbon)
    {
        return $carbon; // No conversion required
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($dateTime)
    {
        if ($dateTime === null) {
            return null;
        }

        if ($dateTime instanceof \DateTime) {
            return Carbon::instance($dateTime);
        }

        throw new UnexpectedTypeException($dateTime, 'The type of $value should be a DateTime or null.');
    }
}
