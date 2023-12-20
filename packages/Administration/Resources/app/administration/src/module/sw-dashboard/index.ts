/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-dashboard-index', () => import('./page/sw-dashboard-index'));


/**
 * @package services-settings
 *
 * @private
 */
SnapAdmin.Module.register('sw-dashboard', {
    type: 'core',
    name: 'dashboard',
    title: 'sw-dashboard.general.mainMenuItemGeneral',
    description: 'sw-dashboard.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'regular-tachometer',
    routes: {
        index: {
            components: {
                default: 'sw-dashboard-index',
            },
            path: 'index',
        },
    },

    navigation: [{
        id: 'sw-dashboard',
        label: 'sw-dashboard.general.mainMenuItemGeneral',
        icon: 'regular-tachometer',
        path: 'sw.dashboard.index',
        position: 10,
    }],
});
