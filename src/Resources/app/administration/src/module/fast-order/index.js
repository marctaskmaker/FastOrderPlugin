import './page/fast-order-list';

import deDE from './snippet/de-DE';
import enGB from './snippet/en-GB';

const { Module } = Shopware;

Shopware.Module.register('fast-order', {
    type: 'plugin',
    name: 'FastOrder',
    title: 'fast-order.general.mainMenuItemGeneral',
    description: 'fast-order.general.descriptionTextModule',
    color: '#A092F0',
    icon: 'default-shopping-paper-bag-product',

    snippets: {
        'de-DE': deDE,
        'en-GB': enGB
    },

    routes: {
        list: {
            component: 'fast-order-list',
            path: 'list',
        }
    },

    navigation: [{
        label: 'Fast Order',
        color: '#A092F0',
        path: 'fast.order.list',
        icon: 'default-shopping-paper-bag-product',
        parent: 'sw-order',
        position: 100
    }]

});
