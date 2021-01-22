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
use CoreShop\Bundle\MarketWarehouseBundle\Model\SupplierInterface;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class SupplierWarehouseContext implements Context
{
    private $sharedStorage;
    private $repository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $repository
    )
    {
        $this->sharedStorage = $sharedStorage;
        $this->repository = $repository;
    }

    /**
     * @Transform /^(supplier) warehouse "([^"]+)"$/
     */
    public function getSupplierByName(SupplierInterface $supplier, $identifier)
    {
        $warehoue = $this->repository->findBy(['identifier' => $identifier, 'supplier__id' => $supplier->getId()]);

        Assert::eq(
            count($warehoue),
            1,
            sprintf('%d suppliers has been found with name "%s".', count($warehoue), $identifier)
        );

        return reset($warehoue);
    }

    /**
     * @Transform /^warehoue/
     */
    public function warehoue()
    {
        return $this->sharedStorage->get('warehouse');
    }
}
