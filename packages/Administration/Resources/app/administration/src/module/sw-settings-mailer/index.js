/**
 * @package services-settings
 */
/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-mailer-smtp', () => import('./component/sw-settings-mailer-smtp'));
SnapAdmin.Component.register('sw-settings-mailer', () => import('./page/sw-settings-mailer'));
/* eslint-enable max-len, sw-deprecation-rules/private-feature-declarations */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
SnapAdmin.Module.register('sw-settings-mailer', {
    type: 'core',
    name: 'settings-mailer',
    title: 'sw-settings-mailer.general.mainMenuItemGeneral',
    description: 'sw-settings-mailer.general.description',
    icon: 'settings',

    routes: {
        index: {
            component: 'sw-settings-mailer',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index.system',
                privilege: 'system.system_config',
            },
        },
    },

    settingsItem: {
        group: 'system',
        to: 'sw.settings.mailer.index',
        icon: 'mail',
        privilege: 'system.system_config',
    },
});
