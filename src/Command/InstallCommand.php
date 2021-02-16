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

namespace CoreShop\Bundle\MarketWarehouseBundle\Command;

use CoreShop\Bundle\CoreBundle\Command\AbstractInstallCommand;
use CoreShop\Component\Pimcore\DataObject\ClassUpdate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class InstallCommand extends AbstractInstallCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('coreshop-market-warehouse:install')
            ->setHidden(true);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->writeln('Adapting CoreShop Classes for MarketWarehouseBundle');

        $classUpdater = new ClassUpdate(
            $this->kernel->getContainer()->getParameter('coreshop.model.order.pimcore_class_name')
        );

        if (!$classUpdater->hasField('packages')) {
            $packagesField = [
                'fieldtype' => 'coreShopRelations',
                'stack' => 'coreshop_market_warehouse.order_package',
                'relationType' => true,
                'objectsAllowed' => true,
                'assetsAllowed' => false,
                'documentsAllowed' => false,
                'width' => null,
                'height' => 0,
                'maxItems' => null,
                'assetUploadPath' => null,
                'queryColumnType' => 'text',
                'assetTypes' => array(),
                'documentTypes' => array(),
                'classes' => array(),
                'pathFormatterClass' => '',
                'name' => 'packages',
                'title' => 'coreshop.order.packages',
                'tooltip' => '',
                'mandatory' => false,
                'noteditable' => true,
                'index' => false,
                'locked' => null,
                'style' => '',
                'permissions' => null,
                'datatype' => 'data',
                'invisible' => false,
                'visibleGridView' => false,
                'visibleSearch' => false,
            ];

            $classUpdater->insertFieldAfter('items', $packagesField);
            $classUpdater->save();
        }

        return 0;
    }
}
