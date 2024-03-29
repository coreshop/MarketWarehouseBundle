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

namespace CoreShop\Bundle\MarketWarehouseBundle\Rule\DeliveryTime\Condition;

use CoreShop\Bundle\MarketWarehouseBundle\Model\WarehouseInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Model\OrderInterface;

final class PostcodeConditionChecker extends AbstractWarehouseDeliveryTimeConditionChecker
{
    public function isRuleValid(
        WarehouseInterface $subject,
        OrderInterface $order,
        AddressInterface $address,
        array $configuration,
        array $context
    ): bool {
        $postcodes = explode(',', $configuration['postcodes']);

        if ($address->getPostcode()) {
            foreach ($postcodes as $postcode) {
                if ($this->checkPostCode($postcode, $address->getPostcode())) {
                    return $configuration['exclusion'] ? false : true;
                }
            }
        }

        return $configuration['exclusion'] ? true : false;
    }

    private function checkPostCode(string $postcode, string $deliveryPostcode): bool
    {
        //Check if postcode has a range
        $deliveryPostcode = str_replace(' ', '', $deliveryPostcode);
        $postcodes = [$postcode];

        if (strpos($postcode, '-') > 0) {
            $splitted = explode('-', $postcode); //We should now have 2 elements

            if (count($splitted) === 2) {
                $fromPart = $splitted[0];
                $toPart = $splitted[1];

                $fromText = preg_replace('/[0-9]+/', '', $fromPart);
                $toText = preg_replace('/[0-9]+/', '', $toPart);

                if ($fromText === $toText) {
                    $fromNumber = preg_replace('/\D/', '', $fromPart);
                    $toNumber = preg_replace('/\D/', '', $toPart);

                    if ($fromNumber < $toNumber) {
                        $postcodes = [];

                        for ($i = $fromNumber; $i <= $toNumber; $i++) {
                            $postcodes[] = $fromText.$i;
                        }
                    }
                }
            }
        }

        foreach ($postcodes as $code) {
            $deliveryZip = substr($deliveryPostcode, 0, strlen($code));

            if (strtolower($deliveryZip) === strtolower($code)) {
                return true;
            }
        }

        return false;
    }
}
