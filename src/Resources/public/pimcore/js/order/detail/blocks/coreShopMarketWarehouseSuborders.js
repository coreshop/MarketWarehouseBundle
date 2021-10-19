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

pimcore.registerNS('coreshop.order.order.detail.blocks.coreshop_market_warehouse_suborders');
coreshop.order.order.detail.blocks.coreshop_market_warehouse_suborders = Class.create(coreshop.order.order.detail.abstractBlock, {
    initBlock: function () {
        this.subordersStore = new Ext.data.JsonStore({
            data: []
        });

        this.suborders = Ext.create('Ext.panel.Panel', {
            title: t('coreshop_market_warehouse_suborders'),
            border: true,
            scrollable: 'y',
            maxHeight: 360,
            margin: '0 20 20 0',
            iconCls: 'coreshop_icon_suborder',
        });
    },

    getPriority: function () {
        return 10;
    },

    getPosition: function () {
        return 'left';
    },

    getPanel: function () {
        return this.suborders;
    },

    updateSale: function () {
        var _ = this;

        if(!_.suborders.items.length) {
            _.suborders.add([_.generateItemGrid()]);
        }

        Ext.Ajax.request({
            url: Routing.generate('coreshop_market_warehouse_admin_sub_order_orders'),
            params: {id: _.sale.id},
            ignoreErrors: true,
            success: function (response) {
                let data = null;

                try {
                    let responseData = Ext.decode(response.responseText);
                    if (!responseData.hasOwnProperty('data')) {
                        return;
                    }
                    data = responseData.data;
                    _.subordersStore.loadRawData(data);
                } catch (e) {
                    console.log(e);
                }
            }.bind(this),
            failure: function () {
            }.bind(this),
        });
    },

    generateItemGrid: function () {
        let _ = this

        return {
            xtype: 'grid',
            margin: '5 0 15 0',
            cls: 'coreshop-detail-grid',
            store: _.subordersStore,
            columns: [
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'warehouses',
                    text: t('coreshop_market_warehouse_suborders_suppliers'),
                    renderer: function (warehouses) {
                        if (!Array.isArray(warehouses)) {
                            return '';
                        }

                        return warehouses.map((element) => {
                            return element.supplier.name + ' (' + element.identifier + ')';
                        }).join(', ');
                    }
                },
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'carriers',
                    text: t('coreshop_market_warehouse_suborders_carriers'),
                    renderer: function (carriers) {
                        if (!Array.isArray(carriers)) {
                            return '';
                        }

                        return carriers.map((element) => {
                           return element.title;
                        }).join(', ');
                    }
                },
                {
                    xtype: 'gridcolumn',
                    flex: 1,
                    dataIndex: 'subtotalGross',
                    text: t('coreshop_market_warehouse_suborders_subtotal'),
                    renderer: coreshop.util.format.currency.bind(this, _.sale.baseCurrency.isoCode)
                },
                {
                    xtype: 'widgetcolumn',
                    flex: 1,
                    onWidgetAttach: function (col, widget, record) {
                        var cursor = record.data.transitions && record.data.transitions.length > 0 ? 'pointer' : 'default';

                        widget.setText(record.data.stateInfo.label);
                        widget.setIconCls(record.data.transitions && record.data.transitions.length !== 0 ? 'pimcore_icon_open' : '');

                        widget.setStyle('border-radius', '2px');
                        widget.setStyle('cursor', cursor);
                        widget.setStyle('background-color', record.data.stateInfo.color);
                    },
                    widget: {
                        xtype: 'button',
                        margin: '3 0',
                        padding: '1 2',
                        border: 0,
                        defaultBindProperty: null,
                        handler: function (widgetColumn) {
                            var record = widgetColumn.getWidgetRecord();
                            var url = Routing.generate('coreshop_market_warehouse_admin_sub_order_update_state'),
                                transitions = record.get('transitions'),
                                id = record.get('id');

                            if (transitions.length !== 0) {
                                coreshop.order.order.state.changeState.showWindow(url, id, transitions, function (result) {
                                    if (result.success) {
                                        me.panel.reload();
                                    }
                                });
                            }
                        }
                    }
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
                        }
                    }]
                }
            ]
        };
    },
});
