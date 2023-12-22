const {Module} = SnapAdmin;

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-media-index', () => import('./page/sw-media-index'));


// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-media', {
    type: 'core',
    name: 'media',
    title: 'sw-media.general.mainMenuItemGeneral',
    description: 'sw-media.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'image',
    entity: 'media',

    routes: {
        index: {
            components: {
                default: 'sw-media-index',
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
        id: 'sw-content',
        label: 'global.sw-admin-menu.navigation.mainMenuItemContent',
        icon: 'layers-subtract',
        position: 50,
    }, {
        id: 'sw-media',
        label: 'sw-media.general.mainMenuItemGeneral',
        icon: 'regular-image',
        path: 'sw.media.index',
        position: 20,
        parent: 'sw-content',
        privilege: 'media.viewer',
    }],
});
