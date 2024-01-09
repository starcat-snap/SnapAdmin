/**
 * @package system
 */
import './mixin/media-grid-listener.mixin';
import './mixin/media-sidebar-modal.mixin';
import './acl';
import defaultSearchConfiguration from './default-search-configuration';

const { Module } = SnapAdmin;

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-media-index', () => import('./page/sw-media-index'));
SnapAdmin.Component.register('sw-media-grid', () => import('./component/sw-media-grid'));
SnapAdmin.Component.register('sw-media-sidebar', () => import('./component/sidebar/sw-media-sidebar'));
SnapAdmin.Component.register('sw-media-quickinfo-metadata-item', () => import('./component/sidebar/sw-media-quickinfo-metadata-item'));
SnapAdmin.Component.register('sw-media-quickinfo-usage', () => import('./component/sidebar/sw-media-quickinfo-usage'));
SnapAdmin.Component.extend('sw-media-collapse', 'sw-collapse', () => import('./component/sw-media-collapse'));
SnapAdmin.Component.register('sw-media-folder-info', () => import('./component/sidebar/sw-media-folder-info'));
SnapAdmin.Component.register('sw-media-quickinfo', () => import('./component/sidebar/sw-media-quickinfo'));
SnapAdmin.Component.register('sw-media-quickinfo-multiple', () => import('./component/sidebar/sw-media-quickinfo-multiple'));
SnapAdmin.Component.register('sw-media-display-options', () => import('./component/sw-media-display-options'));
SnapAdmin.Component.register('sw-media-breadcrumbs', () => import('./component/sw-media-breadcrumbs'));
SnapAdmin.Component.register('sw-media-library', () => import('./component/sw-media-library'));
SnapAdmin.Component.register('sw-media-modal-v2', () => import('./component/sw-media-modal-v2'));
/* eslint-enable max-len, sw-deprecation-rules/private-feature-declarations */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-media', {
    type: 'core',
    name: 'media',
    title: 'sw-media.general.mainMenuItemGeneral',
    description: 'sw-media.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
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
        id: 'sw-system',
        label: 'global.sw-admin-menu.navigation.mainMenuItemSystem',
        icon: 'settings-2',
        position: 990,

    }, {
        id: 'sw-media',
        label: 'sw-media.general.mainMenuItemGeneral',
        icon: 'photo',
        path: 'sw.media.index',
        position: 20,
        parent: 'sw-system',
        privilege: 'media.viewer',
    }],

    defaultSearchConfiguration,
});
