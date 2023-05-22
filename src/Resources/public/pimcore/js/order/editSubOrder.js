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

pimcore.registerNS('coreshop.order.order.editSubOrder');
coreshop.order.order.editSubOrder = {
    showWindow: function (subOrder, callback) {
        var subtotalGross = '';
        if(subOrder.get('subtotalGross')) {
            subtotalGross = coreshop.util.format.currency(subOrder.get('baseCurrency').isoCode, subOrder.get('subtotalGross'));
        }

        var shippingGross = '';
        if(subOrder.get('shippingGross')) {
            shippingGross = coreshop.util.format.currency(subOrder.get('baseCurrency').isoCode, subOrder.get('shippingGross'));
        }

        var window = new Ext.window.Window({
            width: 800,
            height: 600,
            resizeable: false,
            modal: true,
            layout: 'fit',
            items: [{
                xtype: 'form',
                bodyStyle: 'padding:20px 5px 20px 5px;',
                border: false,
                autoScroll: true,
                forceLayout: true,
                anchor: '100%',
                fieldDefaults: {
                    labelWidth: 150
                },
                buttons: [
                    {
                        text: t('OK'),
                        handler: function (btn) {
                            window.close();
                            window.destroy();
                        },
                        iconCls: 'pimcore_icon_apply'
                    }
                ],
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: t('coreshop_market_warehouse_sub_order_subtotal'),
                        name: 'subtotalGross',
                        disabled: true,
                        value: subtotalGross
                    },
                    {
                        xtype: 'textfield',
                        fieldLabel: t('coreshop_market_warehouse_sub_order_shipping'),
                        name: 'shippingGross',
                        disabled: true,
                        value: shippingGross
                    },
                    {
                        xtype: 'gridpanel',
                        style: 'margin: 5px 0;',
                        title: t('coreshop_market_warehouse_sub_order_suppliers'),
                        editable: true,
                        store: new Ext.data.JsonStore({
                            data: subOrder.get('warehouses'),
                            fields: ['supplier', 'identifier']
                        }),
                        columns: [
                            {
                                text: t('coreshop_market_warehouse_sub_order_supplier'),
                                dataIndex: 'supplier',
                                flex: 1,
                                renderer: function (supplier) {
                                    return supplier.name ? supplier.name : '';
                                }
                            },
                            {
                                text: t('coreshop_market_warehouse_sub_order_warehouse'),
                                dataIndex: 'identifier',
                                flex: 1
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
                                        let record = grid.getStore().getAt(rowIndex);
                                        pimcore.helpers.openObject(record.get('o_id'));
                                        window.close();
                                        window.destroy();
                                    }
                                }]
                            },
                        ]
                    },
                    {
                        xtype: 'gridpanel',
                        style: 'margin: 8px 0;',
                        title: t('coreshop_market_warehouse_sub_order_carriers'),
                        editable: true,
                        store: new Ext.data.JsonStore({
                            data: subOrder.get('carriers'),
                            fields: ['title', 'trackingUrl']
                        }),
                        columns: [
                            {
                                text: t('coreshop_market_warehouse_sub_order_carrier'),
                                dataIndex: 'title',
                                flex: 1
                            },
                            /*{
                                text: t('coreshop_market_warehouse_sub_order_carrier_tracking_url'),
                                dataIndex: 'trackingUrl',
                                flex: 1
                            }*/
                            {
                                menuDisabled: true,
                                sortable: false,
                                xtype: 'actioncolumn',
                                width: 32,
                                items: [{
                                    iconCls: 'pimcore_icon_open',
                                    tooltip: t('open'),
                                    handler: function (grid, rowIndex) {
                                        let record = grid.getStore().getAt(rowIndex);
                                        pimcore.helpers.openObject(record.get('o_id'));
                                        window.close();
                                        window.destroy();
                                    }
                                }]
                            },
                        ]
                    },
                    {
                        xtype: 'button',
                        fieldLabel: '',
                        style: 'margin: 5px 0;',
                        tooltip: t('open'),
                        handler: function (widgetColumn) {
                            pimcore.helpers.openObject(subOrder.get('o_id'), 'object');
                            window.close();
                        },
                        listeners: {
                            beforerender: function (widgetColumn) {
                                widgetColumn.setText(Ext.String.format(t('coreshop_market_warehouse_open_order_sub_order')));
                            }
                        }
                    },
                    {
                        xtype: 'gridpanel',
                        style: 'margin: 5px 0;',
                        title: t('coreshop_market_warehouse_sub_order_items'),
                        editable: true,
                        store: new Ext.data.JsonStore({
                            data: subOrder.get('items'),
                            fields: ['_itemName', 'quantity', 'subtotalGross']
                        }),
                        columns: [
                            {
                                text: t('coreshop_market_warehouse_sub_order_item'),
                                dataIndex: '_itemName', flex: 2
                            },
                            {
                                text: t('coreshop_market_warehouse_sub_order_item_quantity'),
                                dataIndex: 'quantity',
                                flex: 1
                            },
                            {
                                text: t('coreshop_market_warehouse_sub_order_item_subtotal'),
                                dataIndex: 'subtotalGross',
                                flex: 1,
                                renderer: coreshop.util.format.currency.bind(this, subOrder.get('baseCurrency').isoCode)
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
                                        let record = grid.getStore().getAt(rowIndex);
                                        pimcore.helpers.openObject(record.get('o_id'));
                                        window.close();
                                        window.destroy();
                                    }
                                }]
                            },
                        ]
                    }/*,
                    {
                        xtype: 'gridpanel',
                        title: t('coreshop_market_warehouse_order_packages'),
                        editable: true,
                        store: new Ext.data.JsonStore({
                            data: subOrder.get('packages'),
                            fields: ['warehouse', 'subtotalGross']
                        }),
                        columns: [
                            {text: t('coreshop_market_warehouse_warehouse'), dataIndex: 'warehouse', flex: 1},
                            {
                                text: t('coreshop_market_warehouse_order_package_subtotal'),
                                dataIndex: 'subtotalGross',
                                flex: 1,
                                renderer: coreshop.util.format.currency.bind(this, subOrder.get('order').baseCurrency.isoCode)
                            }
                        ]
                    }*/
                ]
            }]
        });

        window.show();
    }

};
