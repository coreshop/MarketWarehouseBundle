<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Bundle\MarketWarehouseBundle\SubOrder;

final class SubOrderStates
{
    const STATE_INITIALIZED = 'initialized';
    const STATE_NEW = 'new';
    const STATE_CONFIRMED = 'confirmed';
    const STATE_CANCELLED = 'cancelled';
    const STATE_COMPLETE = 'complete';
}
