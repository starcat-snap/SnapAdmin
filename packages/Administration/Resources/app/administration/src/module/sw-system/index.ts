/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */

/**
 * @package services-settings
 *
 * @private
 */
SnapAdmin.Module.register('sw-system', {
    type: 'core',
    name: 'system',
    title: 'sw-system.general.mainMenuItemGeneral',
    description: 'sw-system.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'settings',
    routes: {
        index: {
            components: {
                default: 'sw-dashboard-index',
            },
            path: 'index',
        },
    },

    navigation: [{
        id: 'sw-system',
        label: 'sw-system.general.mainMenuItemGeneral',
        icon: 'settings',
        path: 'sw.dashboard.index',
        position: 100,
    }],
});
