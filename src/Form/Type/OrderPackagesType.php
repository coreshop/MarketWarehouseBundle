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

namespace CoreShop\Bundle\MarketWarehouseBundle\Form\Type;

use CoreShop\Bundle\CoreBundle\Form\Type\Checkout\CarrierChoiceType;
use CoreShop\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

final class OrderPackagesType extends AbstractResourceType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('show_default_carrier_selection', false);
        $resolver->setDefined('cart');
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['show_default_carrier_selection']) {
            $builder
                ->add('carrier', CarrierChoiceType::class, [
                    'constraints' => [new Valid(['groups' => $this->validationGroups]), new NotBlank(['groups' => $this->validationGroups])],
                    'expanded' => true,
                    'label' => 'coreshop.ui.carrier',
                    'cart' => $options['cart'],
                ]);
        }

        $builder
            ->add('packages', CollectionType::class, [
                'entry_type' => OrderPackageType::class,
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => true,
                'label' => 'coreshop.ui.order.packages',
                'entry_options' => ['constraints' => new Valid(['groups' => $this->validationGroups])]
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'coreshop.ui.comment',
            ]);
    }

    public function getBlockPrefix()
    {
        return 'coreshop_market_warehouse_order_packages';
    }
}
