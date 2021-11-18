<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 CORS GmbH (https://cors.gmbh)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Behat\MarketWarehouseBundle\Context\Transform;

use Behat\Behat\Context\Context;
use CoreShop\Behat\Service\SharedStorageInterface;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class SupplierCarrierContext implements Context
{
    public function __construct(
        private SharedStorageInterface $sharedStorage,
        private RepositoryInterface $repository
    ) {
    }

    /**
     * @Transform /^supplier-carrier(?:|s) "([^"]+)"$/
     */
    public function getSupplierCarrierByTitle($title)
    {
        $suppliers = $this->repository->findBy(['title' => $title]);

        Assert::eq(
            count($suppliers),
            1,
            sprintf('%d supplier-carrier has been found with title "%s".', count($suppliers), $title)
        );

        return reset($suppliers);
    }

    /**
     * @Transform supplier-carrier
     */
    public function supplierCarrier()
    {
        return $this->sharedStorage->get('supplier-carrier');
    }
}
