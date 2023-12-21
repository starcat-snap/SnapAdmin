import './acl';
/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */

SnapAdmin.Component.register('sw-extension-my-extensions-index', () => import('./page/sw-extension-my-extensions-index'));
/* eslint-enable max-len, sw-deprecation-rules/private-feature-declarations */


/**
 * @package services-settings
 * @private
 */
SnapAdmin.Module.register('sw-extension', {
    type: 'core',
    title: 'sw-extension-store.title',
    description: 'sw-extension-store.descriptionTextModule',
    icon: 'plug',
    version: '1.0.0',
    targetVersion: '1.0.0',
    entity: 'extension',
    display: !SnapAdmin.Context.app.disableExtensions,
    routes: {
        'my-extensions': {
            path: 'my-extensions',
            component: 'sw-extension-my-extensions-index',
        },
    },
    navigation: [
        {
            id: 'sw-extension',
            label: 'sw-extension.mainMenu.mainMenuItemExtensionStore',
            icon: 'plug',
            position: 70,
        },
        {
            id: 'sw-extension-my-extensions',
            parent: 'sw-extension',
            label: 'sw-extension.mainMenu.purchased',
            path: 'sw.extension.my-extensions',
            privilege: 'system.plugin_maintain',
            position: 10,
        },
    ],
});
