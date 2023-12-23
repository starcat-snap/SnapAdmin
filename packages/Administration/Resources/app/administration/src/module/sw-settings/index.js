/**
 * @package services-settings
 */
import './mixin/sw-settings-list.mixin';
import './acl';

const { Module } = SnapAdmin;

/* eslint-disable sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-item', () => import('./component/sw-settings-item'));
SnapAdmin.Component.register('sw-system-config', () => import('./component/sw-system-config'));
SnapAdmin.Component.register('sw-settings-index', () => import('./page/sw-settings-index'));
/* eslint-enable sw-deprecation-rules/private-feature-declarations */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-settings', {
    type: 'core',
    name: 'settings',
    title: 'sw-settings.general.mainMenuItemGeneral',
    icon: 'settings',

    routes: {
        index: {
            component: 'sw-settings-index',
            path: 'index',
            icon: 'settings',
            redirect: {
                name: 'sw.settings.index.system',
            },
            children: {
                system: {
                    path: 'system',
                    meta: {
                        component: 'sw-settings-index',
                        parentPath: 'sw.settings.index',
                    },
                },
                plugins: {
                    path: 'plugins',
                    meta: {
                        component: 'sw-settings-index',
                        parentPath: 'sw.settings.index',
                    },
                },
            },
        },
    },

    navigation: [{
        id: 'sw-settings',
        label: 'sw-settings.general.mainMenuItemGeneral',
        color: '#9AA8B5',
        icon: 'settings',
        path: 'sw.settings.index',
        position: 999,
    }],
});
