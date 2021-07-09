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

namespace CoreShop\Bundle\MarketWarehouseBundle\Validator\Constraints;

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\MarketWarehouseBundle\Package\Calculator\ShippingDateValidatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

class OrderPackageShippingDateValidator extends ConstraintValidator
{
    private ShippingDateValidatorInterface $shippingDateValidator;

    public function __construct(ShippingDateValidatorInterface $shippingDateValidator)
    {
        $this->shippingDateValidator = $shippingDateValidator;
    }

    public function validate($value, Constraint $constraint): void
    {
        /**
         * @var OrderPackageInterface $value
         */
        Assert::isInstanceOf($value, OrderPackageInterface::class);

        /**
         * @var OrderPackageShippingDate $constraint
         */
        Assert::isInstanceOf($constraint, OrderPackageShippingDate::class);

        if (null === $value->getWishedShippingDate()) {
            return;
        }

        if (!$this->shippingDateValidator->isShippingDateValid($value, $value->getWishedShippingDate())) {
            $this->context->buildViolation($constraint->message)
                ->atPath('wishedShippingDate')
                ->addViolation();
        }
    }
}
