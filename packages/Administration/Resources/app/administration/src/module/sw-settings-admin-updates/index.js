import './page/sw-settings-admin-updates-wizard';
import './page/sw-settings-admin-updates-index';
import './view/sw-settings-admin-updates-info';
import './view/sw-settings-admin-updates-requirements';
import './view/sw-settings-admin-updates-plugins';
import './acl';

const { Module } = SnapAdmin;

/**
 * @private
 */
Module.register('sw-settings-admin-updates', {
    type: 'core',
    name: 'settings-admin-updates',
    title: 'sw-settings-admin-updates.general.emptyTitle',
    description: 'sw-settings-admin-updates.general.emptyTitle',
    version: '1.0.0',
    targetVersion: '1.0.0',
    icon: 'settings',

    routes: {
        wizard: {
            component: 'sw-settings-admin-updates-wizard',
            path: 'wizard',
            meta: {
                parentPath: 'sw.settings.index.system',
                privilege: 'system.core_update',
            },
        },
    },

    settingsItem: {
        privilege: 'system.core_update',
        group: 'system',
        to: 'sw.settings.admin.updates.wizard',
        icon: 'refresh',
    },
});
