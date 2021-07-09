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

namespace CoreShop\Bundle\MarketWarehouseBundle\Form\Extension;

use CoreShop\Bundle\MarketWarehouseBundle\Form\DataTransformer\CarbonToDateTimeTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Sander Marechal <s.marechal@jejik.com>
 */
class CarbonDateExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['use_carbon']) {
            $builder->addModelTransformer(new CarbonToDateTimeTransformer());
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('use_carbon', false);
    }


    public static function getExtendedTypes(): array
    {
        return [DateType::class];
    }
}
