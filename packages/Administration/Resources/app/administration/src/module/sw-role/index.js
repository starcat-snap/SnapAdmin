/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
import './acl';


SnapAdmin.Component.register('sw-role-list', () => import('./page/sw-role-list'));


/**
 * @package services-settings
 *
 * @private
 */
SnapAdmin.Module.register('sw-role', {
    type: 'core',
    name: 'roles',
    title: 'sw-role.general.mainMenuItemGeneral',
    description: 'sw-role.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'roles',
    routes: {
        index: {
            components: {
                default: 'sw-role-list',
            },
            path: 'index',
            meta: {
                privilege: 'role.viewer',
                appSystem: {
                    view: 'list',
                },
            },
        },
    },

    navigation: [{
        parent: 'sw-system',
        label: 'sw-role.general.mainMenuItemList',
        icon: 'roles',
        path: 'sw.role.index',
    }],
});
