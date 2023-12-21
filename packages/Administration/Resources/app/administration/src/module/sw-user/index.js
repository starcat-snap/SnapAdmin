/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
import './acl';


SnapAdmin.Component.register('sw-user-list', () => import('./page/sw-user-list'));


/**
 * @package services-settings
 *
 * @private
 */
SnapAdmin.Module.register('sw-user', {
    type: 'core',
    name: 'users',
    title: 'sw-user.general.mainMenuItemGeneral',
    description: 'sw-user.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'users',
    routes: {
        index: {
            components: {
                default: 'sw-user-list',
            },
            path: 'index',
            meta: {
                privilege: 'user.viewer',
                appSystem: {
                    view: 'list',
                },
            },
        },
    },

    navigation: [{
        id: 'sw-user',
        label: 'sw-user.general.mainMenuItemGeneral',
        icon: 'users',
        position: 40,
    }, {
        parent: 'sw-user',
        label: 'sw-user.general.mainMenuItemList',
        icon: 'users',
        path: 'sw.user.index',
    }],
});
