/*
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 *
 */

pimcore.registerNS('coreshop.order.order.detail.blocks.coreshop_market_warehouse_packages');
coreshop.order.order.detail.blocks.coreshop_market_warehouse_packages = Class.create(coreshop.order.order.detail.abstractBlock, {
    initBlock: function () {
        this.packagesStore = new Ext.data.JsonStore({
            data: []
        });

        this.packages = Ext.create('Ext.panel.Panel', {
            title: t('coreshop_market_warehouse_packages'),
            border: true,
            scrollable: 'y',
            maxHeight: 360,
            margin: '0 20 20 0',
            iconCls: 'coreshop_icon_package',
        });
    },

    getPriority: function () {
        return 15;
    },

    getPosition: function () {
        return 'left';
    },

    getPanel: function () {
        return this.packages;
    },

    updateSale: function () {
        var me = this;

        if (!me.packages.items.length) {
            me.packages.add([me.generateItemGrid()]);
        }

        me.packagesStore.loadRawData(this.sale.packages);
    },

    generateItemGrid: function () {
        let me = this

        return {
            xtype: 'grid',
            margin: '5 0 15 0',
            cls: 'coreshop-detail-grid',
            store: me.packagesStore,
            columns: [
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'warehouse',
                    text: t('coreshop_market_warehouse_sub_order_supplier'),
                    renderer: function (warehouse) {
                        return warehouse.supplier.name + ' (' + warehouse.identifier + ')';
                    }
                },
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'carrier',
                    text: t('coreshop_market_warehouse_sub_order_carrier'),
                    renderer: function (carrier) {
                        return carrier.title;
                    }
                },
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'subtotalGross',
                    text: t('coreshop_market_warehouse_sub_order_subtotal'),
                    renderer: coreshop.util.format.currency.bind(this, me.sale.baseCurrency.isoCode)
                },
                {
                    menuDisabled: true,
                    sortable: false,
                    xtype: 'actioncolumn',
                    width: 32,
                    items: [{
                        iconCls: 'pimcore_icon_open',
                        tooltip: t('open'),
                        handler: function (grid, rowIndex) {
                            coreshop.order.helper.openOrder(grid.getStore().getAt(rowIndex).get('id'));
                        }
                    }]
                }
            ]
        };
    },
});
