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

use CoreShop\Bundle\MarketWarehouseBundle\Model\OrderPackageInterface;
use CoreShop\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Valid;

final class OrderPackageType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();

            if (!$data instanceof OrderPackageInterface) {
                return;
            }

            if (null === $data->getWarehouse()) {
                return;
            }

            $event->getForm()->add('carrier', OrderPackageCarrierChoiceType::class, [
                'constraints' => [new Valid(['groups' => $this->validationGroups]), new NotBlank(['groups' => $this->validationGroups])],
                'expanded' => true,
                'label' => 'coreshop.ui.package.carrier',
                'package' => $data
            ]);

            $event->getForm()->add('wishedShippingDate', DateType::class, [
                'constraints' => [],
                'label' => 'coreshop.ui.package.shipping_date',
                'use_carbon' => true
            ]);
        });
    }

    public function getBlockPrefix()
    {
        return 'coreshop_market_warehouse_order_package';
    }
}
