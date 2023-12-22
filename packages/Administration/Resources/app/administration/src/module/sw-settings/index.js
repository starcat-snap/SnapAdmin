const {Module} = SnapAdmin;

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-index', () => import('./page/sw-settings-index'));


// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-settings', {
    type: 'core',
    name: 'media',
    title: 'sw-settings.general.mainMenuItemGeneral',
    description: 'sw-settings.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'settings',

    routes: {
        index: {
            components: {
                default: 'sw-settings-index',
            },
            path: 'index/:folderId?',
            props: {
                default: (route) => {
                    return {
                        routeFolderId: route.params.folderId,
                    };
                },
            },
            meta: {
                privilege: 'media.viewer',
            },
        },
    },

    navigation: [{
        id: 'sw-settings',
        label: 'sw-settings.general.mainMenuItemGeneral',
        icon: 'settings',
        path: 'sw.settings.index',
        position: 1000,
    }],
});
