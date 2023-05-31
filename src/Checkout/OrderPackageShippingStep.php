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

namespace CoreShop\Bundle\MarketWarehouseBundle\Checkout;

use CoreShop\Bundle\MarketWarehouseBundle\Form\Type\OrderPackagesType;
use CoreShop\Bundle\MarketWarehouseBundle\Model\SubOrderInterface;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Checkout\CheckoutException;
use CoreShop\Component\Order\Checkout\CheckoutStepInterface;
use CoreShop\Component\Order\Checkout\OptionalCheckoutStepInterface;
use CoreShop\Component\Order\Checkout\ValidationCheckoutStepInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Shipping\Validator\ShippableCarrierValidatorInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class OrderPackageShippingStep implements CheckoutStepInterface, OptionalCheckoutStepInterface, ValidationCheckoutStepInterface
{
    private ShippableCarrierValidatorInterface $shippableCarrierValidator;
    private FormFactoryInterface $formFactory;
    private CartManagerInterface $cartManager;

    public function __construct(
        ShippableCarrierValidatorInterface $shippableCarrierValidator,
        FormFactoryInterface $formFactory,
        CartManagerInterface $cartManager
    ) {
        $this->shippableCarrierValidator = $shippableCarrierValidator;
        $this->formFactory = $formFactory;
        $this->cartManager = $cartManager;
    }

    public function getIdentifier(): string
    {
        return 'shipping';
    }

    public function isRequired(OrderInterface $cart): bool
    {
        Assert::isInstanceOf($cart, \CoreShop\Component\Core\Model\OrderInterface::class);

        return $cart->hasShippableItems();
    }

    public function doAutoForward(OrderInterface $cart): bool
    {
        Assert::isInstanceOf($cart, \CoreShop\Component\Core\Model\OrderInterface::class);

        return $cart->hasShippableItems() === false;
    }

    public function validate(OrderInterface $cart): bool
    {
        /**
         * @var SubOrderInterface $cart
         */
        Assert::isInstanceOf($cart, SubOrderInterface::class);

        if ($cart->hasShippableItems() === false) {
            return true;
        }

        if ($cart->hasShippableItems() === true && !$cart->getShippingAddress() instanceof AddressInterface) {
            return false;
        }

        $packageCarriers = true;

        foreach ($cart->getPackages() as $package) {
            if (null === $package->getWarehouse()) {
                continue;
            }

            if (null === $package->getCarrier()) {
                $packageCarriers = false;
                break;
            }

            if (!$this->shippableCarrierValidator->isCarrierValid($package->getCarrier(), $package, $cart->getShippingAddress())) {
                $packageCarriers = false;
                break;
            }
        }

        return $packageCarriers;
    }

    public function commitStep(OrderInterface $cart, Request $request): bool
    {
        $form = $this->createForm($request, $cart);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $cart = $form->getData();

                $this->cartManager->persistCart($cart);

                return true;
            }

            throw new CheckoutException('Shipping Form is invalid', 'coreshop.ui.error.coreshop_checkout_shipping_form_invalid');
        }

        return false;
    }

    public function prepareStep(OrderInterface $cart, Request $request): array
    {
        return [
            'form' => $this->createForm($request, $cart)->createView(),
        ];
    }

    private function createForm(Request $request, OrderInterface $cart): FormInterface
    {
        /**
         * @var SubOrderInterface $cart
         */
        Assert::isInstanceOf($cart, SubOrderInterface::class);

        $hasNonSupplierPackage = false;

        foreach ($cart->getPackages() as $package) {
            if (null === $package->getWarehouse()) {
                $hasNonSupplierPackage = true;
                break;
            }
        }

        $form = $this->formFactory->createNamed('coreshop', OrderPackagesType::class, $cart, [
            'cart' => $cart,
            'show_default_carrier_selection' => $hasNonSupplierPackage
        ]);

        if ($request->isMethod('post')) {
            $form = $form->handleRequest($request);
        }

        return $form;
    }
}
