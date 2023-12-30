/**
 * @package services-settings
 */
import './acl';

const { Module } = SnapAdmin;

/* eslint-disable sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-cache-index', () => import('./page/sw-settings-cache-index'));
SnapAdmin.Component.register('sw-settings-cache-modal', () => import('./component/sw-settings-cache-modal'));
/* eslint-enable sw-deprecation-rules/private-feature-declarations */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Module.register('sw-settings-cache', {
    type: 'core',
    name: 'settings-cache',
    title: 'sw-settings-cache.general.mainMenuItemGeneral',
    description: 'sw-settings-cache.general.description',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'settings',
    favicon: 'icon-module-settings.png',

    routes: {
        index: {
            component: 'sw-settings-cache-index',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index.system',
                privilege: 'system.clear_cache',
            },
        },
    },

    settingsItem: {
        privilege: 'system.clear_cache',
        group: 'system',
        to: 'sw.settings.cache.index',
        icon: 'files',
    },
});
