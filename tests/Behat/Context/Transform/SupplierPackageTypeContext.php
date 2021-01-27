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

final class SupplierPackageTypeContext implements Context
{
    private $sharedStorage;
    private $repository;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        RepositoryInterface $repository
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->repository = $repository;
    }

    /**
     * @Transform /^package-type "([^"]+)"$/
     */
    public function getByName($identifier)
    {
        $packageType = $this->repository->findBy(['identifier' => $identifier]);

        Assert::eq(
            count($packageType),
            1,
            sprintf('%d package-types has been found with name "%s".', count($packageType), $identifier)
        );

        return reset($packageType);
    }

    /**
     * @Transform /^package-type/
     */
    public function warehoue()
    {
        return $this->sharedStorage->get('package-type');
    }
}
