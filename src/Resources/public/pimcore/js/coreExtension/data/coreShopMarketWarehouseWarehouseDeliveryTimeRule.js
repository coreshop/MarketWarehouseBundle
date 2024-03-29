/*
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 *
 */

pimcore.registerNS('pimcore.object.classes.data.coreShopMarketWarehouseWarehouseDeliveryTimeRule');
pimcore.object.classes.data.coreShopMarketWarehouseWarehouseDeliveryTimeRule = Class.create(coreshop.object.classes.data.data, {

    type: 'coreShopMarketWarehouseWarehouseDeliveryTimeRule',
    /**
     * define where this datatype is allowed
     */
    allowIn: {
        object: true,
        objectbrick: false,
        fieldcollection: false,
        localizedfield: false
    },

    initialize: function (treeNode, initData) {
        this.initData(initData);

        this.treeNode = treeNode;
    },

    getLayout: function ($super) {
        $super();

        this.specificPanel.removeAll();

        return this.layout;
    },

    getTypeName: function () {
        return t('coreshop_market_warehouse_warehouse_delivery_time_rules');
    },

    getGroup: function () {
        return 'coreshop_market_warehouse';
    },

    getIconClass: function () {
        return 'coreshop_icon_market_warehouse_warehouse_delivery_time_rules';
    }
});
