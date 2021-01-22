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
use CoreShop\Component\Store\Repository\StoreRepositoryInterface;
use Webmozart\Assert\Assert;

final class SupplierContext implements Context
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
     * @Transform /^supplier(?:|s) "([^"]+)"$/
     */
    public function getSupplierByName($name)
    {
        $suppliers = $this->repository->findBy(['name' => $name]);

        Assert::eq(
            count($suppliers),
            1,
            sprintf('%d suppliers has been found with name "%s".', count($suppliers), $name)
        );

        return reset($suppliers);
    }
}
