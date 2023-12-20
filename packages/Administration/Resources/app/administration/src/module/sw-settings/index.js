import './acl';

const { Module } = SnapAdmin;

/* eslint-disable sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-index', () => import('./page/sw-settings-index'));


// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-settings', {
    type: 'core',
    name: 'settings',
    title: 'sw-settings.general.mainMenuItemGeneral',
    icon: 'regular-cog',

    routes: {
        index: {
            component: 'sw-settings-index',
            path: 'index',
            icon: 'regular-cog',
            redirect: {
                name: 'sw.settings.index.system',
            },
            children: {
                system: {
                    path: 'system',
                    meta: {
                        component: 'sw-dashboard-index',
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
        icon: 'regular-cog',
        path: 'sw.settings.index',
        position: 999,
    }],
});
