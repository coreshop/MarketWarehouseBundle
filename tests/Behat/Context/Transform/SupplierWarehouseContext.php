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

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Transform;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class SupplierWarehouseContext implements Context
{
    private $sharedStorage;
    private $repository;
    private $supplierRepository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $repository,
        RepositoryInterface $supplierRepository
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->repository = $repository;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @Transform /^supplier-warehouse "([^"]+)"->"([^"]+)"$/
     */
    public function getWarehouseBySupplierAndIdentifier(string $supplierName, string $identifier)
    {
        $suppliers = $this->supplierRepository->findBy(['name' => $supplierName]);

        Assert::eq(
            count($suppliers),
            1,
            sprintf('%d suppliers has been found with name "%s".', count($suppliers), $supplierName)
        );

        $supplier = reset($suppliers);

        $warehouses = $this->repository->findBy(['identifier' => $identifier, 'supplier__id' => $supplier->getId()]);

        Assert::eq(
            count($warehouses),
            1,
            sprintf('%d suppliers has been found with name "%s".', count($warehouses), $identifier)
        );

        return reset($warehouses);
    }

    /**
     * @Transform /^warehoue/
     */
    public function warehoue()
    {
        return $this->sharedStorage->get('warehouse');
    }
}
