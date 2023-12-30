import './service/flow-builder.service';
import './acl';

import flowState from './state/flow.state';

const { Module, State } = SnapAdmin;
State.registerModule('swFlowState', flowState);

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-flow-index', () => import('./page/sw-flow-index'));
SnapAdmin.Component.register('sw-flow-detail', () => import('./page/sw-flow-detail'));
SnapAdmin.Component.register('sw-flow-detail-flow', () => import('./view/detail/sw-flow-detail-flow'));
SnapAdmin.Component.register('sw-flow-sequence-modal', () => import('./component/sw-flow-sequence-modal'));
SnapAdmin.Component.register('sw-flow-detail-general', () => import('./view/detail/sw-flow-detail-general'));
SnapAdmin.Component.register('sw-flow-list', () => import('./view/listing/sw-flow-list'));
SnapAdmin.Component.register('sw-flow-list-flow-templates', () => import('./view/listing/sw-flow-list-flow-templates'));
SnapAdmin.Component.register('sw-flow-trigger', () => import('./component/sw-flow-trigger'));
SnapAdmin.Component.register('sw-flow-sequence', () => import('./component/sw-flow-sequence'));
SnapAdmin.Component.register('sw-flow-sequence-action', () => import('./component/sw-flow-sequence-action'));
SnapAdmin.Component.register('sw-flow-sequence-condition', () => import('./component/sw-flow-sequence-condition'));
SnapAdmin.Component.register('sw-flow-sequence-selector', () => import('./component/sw-flow-sequence-selector'));
SnapAdmin.Component.register('sw-flow-sequence-action-error', () => import('./component/sw-flow-sequence-action-error'));
SnapAdmin.Component.register('sw-flow-rule-modal', () => import('./component/modals/sw-flow-rule-modal'));
SnapAdmin.Component.register('sw-flow-tag-modal', () => import('./component/modals/sw-flow-tag-modal'));
SnapAdmin.Component.register('sw-flow-set-order-state-modal', () => import('./component/modals/sw-flow-set-order-state-modal'));
SnapAdmin.Component.register('sw-flow-generate-document-modal', () => import('./component/modals/sw-flow-generate-document-modal'));
SnapAdmin.Component.register('sw-flow-grant-download-access-modal', () => import('./component/modals/sw-flow-grant-download-access-modal'));
SnapAdmin.Component.register('sw-flow-mail-send-modal', () => import('./component/modals/sw-flow-mail-send-modal'));
SnapAdmin.Component.register('sw-flow-create-mail-template-modal', () => import('./component/modals/sw-flow-create-mail-template-modal'));
SnapAdmin.Component.register('sw-flow-event-change-confirm-modal', () => import('./component/modals/sw-flow-event-change-confirm-modal'));
SnapAdmin.Component.register('sw-flow-change-customer-group-modal', () => import('./component/modals/sw-flow-change-customer-group-modal'));
SnapAdmin.Component.register('sw-flow-change-customer-status-modal', () => import('./component/modals/sw-flow-change-customer-status-modal'));
SnapAdmin.Component.register('sw-flow-set-entity-custom-field-modal', () => import('./component/modals/sw-flow-set-entity-custom-field-modal'));
SnapAdmin.Component.register('sw-flow-affiliate-and-campaign-code-modal', () => import('./component/modals/sw-flow-affiliate-and-campaign-code-modal'));
SnapAdmin.Component.register('sw-flow-app-action-modal', () => import('./component/modals/sw-flow-app-action-modal'));
SnapAdmin.Component.register('sw-flow-leave-page-modal', () => import('./component/modals/sw-flow-leave-page-modal'));
/* eslint-enable max-len, sw-deprecation-rules/private-feature-declarations */

/**
 * @private
 * @package services-settings
 */
Module.register('sw-flow', {
    type: 'core',
    name: 'flow',
    title: 'sw-flow.general.mainMenuItemGeneral',
    description: 'sw-flow.general.descriptionTextModule',
    version: '1.0.0',
    targetVersion: '1.0.0',
    color: '#9AA8B5',
    icon: 'settings',
    entity: 'flow',

    routes: {
        index: {
            component: 'sw-flow-index',
            path: 'index',
            meta: {
                parentPath: 'sw.settings.index',
                privilege: 'flow.viewer',
            },
            redirect: {
                name: 'sw.flow.index.flows',
            },
            children: {
                flows: {
                    component: 'sw-flow-list',
                    path: 'flows',
                    meta: {
                        parentPath: 'sw.settings.index',
                        privilege: 'flow.viewer',
                    },
                },
                templates: {
                    component: 'sw-flow-list-flow-templates',
                    path: 'templates',
                    meta: {
                        parentPath: 'sw.settings.index',
                        privilege: 'flow.viewer',
                    },
                },
            },
        },
        detail: {
            component: 'sw-flow-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'sw.flow.index',
                privilege: 'flow.viewer',
            },
            props: {
                default(route) {
                    return {
                        flowId: route.params.id,
                    };
                },
            },
            redirect: {
                name: 'sw.flow.detail.general',
            },
            children: {
                general: {
                    component: 'sw-flow-detail-general',
                    path: 'general',
                    meta: {
                        parentPath: 'sw.flow.index',
                        privilege: 'flow.viewer',
                    },
                },
                flow: {
                    component: 'sw-flow-detail-flow',
                    path: 'flow',
                    meta: {
                        parentPath: 'sw.flow.index',
                        privilege: 'flow.viewer',
                    },
                },
            },
        },
        create: {
            component: 'sw-flow-detail',
            path: 'create',
            meta: {
                parentPath: 'sw.flow.index',
                privilege: 'flow.creator',
            },
            redirect: {
                name: 'sw.flow.create.general',
            },
            children: {
                general: {
                    component: 'sw-flow-detail-general',
                    path: 'general',
                    meta: {
                        parentPath: 'sw.flow.index',
                        privilege: 'flow.viewer',
                    },
                },
                flow: {
                    component: 'sw-flow-detail-flow',
                    path: 'flow',
                    meta: {
                        parentPath: 'sw.flow.index',
                        privilege: 'flow.viewer',
                    },
                },
            },
        },
    },

    settingsItem: {
        group: 'system',
        to: 'sw.flow.index',
        icon: 'cpu',
        privilege: 'flow.viewer',
    },
});
