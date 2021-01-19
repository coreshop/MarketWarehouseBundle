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

pimcore.registerNS('coreshop.market_warehouse.warehouse_delivery_time_rule.actions.actions');

coreshop.market_warehouse.warehouse_delivery_time_rule.actions.days = Class.create(coreshop.rules.actions.abstract, {
    type: 'days',

    getForm: function () {
        var daysValue = 0;

        if (this.data) {
            if (this.data.days) {
                daysValue = this.data.days;
            }
        }

        var days = new Ext.form.Number({
            fieldLabel: t('coreshop_condition_days'),
            name: 'days',
            value: daysValue
        });

        this.form = Ext.create('Ext.form.FieldSet', {
            items: [
                days
            ]
        });

        return this.form;
    }
});
