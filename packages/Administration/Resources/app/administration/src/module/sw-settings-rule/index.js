import './acl';

const { Module } = SnapAdmin;

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-settings-rule-add-assignment-modal', () => import('./component/sw-settings-rule-add-assignment-modal'));
SnapAdmin.Component.register('sw-settings-rule-add-assignment-listing', () => import('./component/sw-settings-rule-add-assignment-listing'));
SnapAdmin.Component.extend('sw-settings-rule-assignment-listing', 'sw-entity-listing', () => import('./component/sw-settings-rule-assignment-listing'));
SnapAdmin.Component.register('sw-settings-rule-category-tree', () => import('./component/sw-settings-rule-category-tree'));
SnapAdmin.Component.extend('sw-settings-rule-tree-item', 'sw-tree-item', () => import('./component/sw-settings-rule-tree-item'));
SnapAdmin.Component.extend('sw-settings-rule-tree', 'sw-tree', () => import('./component/sw-settings-rule-tree'));
SnapAdmin.Component.register('sw-settings-rule-list', () => import('./page/sw-settings-rule-list'));
SnapAdmin.Component.register('sw-settings-rule-detail', () => import('./page/sw-settings-rule-detail'));
SnapAdmin.Component.register('sw-settings-rule-detail-base', () => import('./view/sw-settings-rule-detail-base'));
SnapAdmin.Component.register('sw-settings-rule-detail-assignments', () => import('./view/sw-settings-rule-detail-assignments'));
/* eslint-enable max-len, sw-deprecation-rules/private-feature-declarations */

/**
 * @private
 * @package services-settings
 */
Module.register('sw-settings-rule', {
    type: 'core',
    name: 'settings-rule',
    title: 'sw-settings-rule.general.mainMenuItemGeneral',
    description: 'sw-settings-rule.general.descriptionTextModule',
    color: '#9AA8B5',
    icon: 'settings',
    favicon: 'icon-module-settings.png',
    entity: 'rule',

    routes: {
        index: {
            component: 'sw-settings-rule-list',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index',
                privilege: 'rule.viewer',
            },
        },
        detail: {
            component: 'sw-settings-rule-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.settings.rule.index',
                privilege: 'rule.viewer',
            },
            props: {
                default(route) {
                    return {
                        ruleId: route.params.id,
                    };
                },
            },
            redirect: {
                name: 'sw.settings.rule.detail.base',
            },
            children: {
                base: {
                    component: 'sw-settings-rule-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'sw.settings.rule.index',
                        privilege: 'rule.viewer',
                    },
                },
                assignments: {
                    component: 'sw-settings-rule-detail-assignments',
                    path: 'assignments',
                    meta: {
                        parentPath: 'sw.settings.rule.index',
                        privilege: 'rule.viewer',
                    },
                },
            },
        },
        create: {
            component: 'sw-settings-rule-detail',
            path: 'create',
            meta: {
                parentPath: 'sw.settings.rule.index',
                privilege: 'rule.creator',
            },
            redirect: {
                name: 'sw.settings.rule.create.base',
            },
            children: {
                base: {
                    component: 'sw-settings-rule-detail-base',
                    path: 'base',
                    meta: {
                        parentPath: 'sw.settings.rule.index',
                        privilege: 'rule.viewer',
                    },
                },
            },
        },
    },

    settingsItem: {
        group: 'system',
        to: 'sw.settings.rule.index',
        icon: 'ruler',
        privilege: 'rule.viewer',
    },
});
