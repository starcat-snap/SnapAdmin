/**
 * @package admin
 */

/* @private */
export default () => {
    /* eslint-disable sw-deprecation-rules/private-feature-declarations, max-len */
    SnapAdmin.Component.register('sw-code-editor', () => import('src/app/asyncComponent/form/sw-code-editor'));
    SnapAdmin.Component.register('sw-datepicker', () => import('src/app/asyncComponent/form/sw-datepicker'));
    SnapAdmin.Component.register('sw-chart', () => import('src/app/asyncComponent/base/sw-chart'));
    SnapAdmin.Component.register('sw-help-center-v2', () => import('src/app/asyncComponent/utils/sw-help-center'));
    SnapAdmin.Component.register('sw-help-sidebar', () => import('src/app/asyncComponent/sidebar/sw-help-sidebar'));
    SnapAdmin.Component.register('sw-image-slider', () => import('src/app/asyncComponent/media/sw-image-slider'));
    SnapAdmin.Component.register('sw-media-add-thumbnail-form', () => import('src/app/asyncComponent/media/sw-media-add-thumbnail-form'));
    SnapAdmin.Component.register('sw-media-base-item', () => import('src/app/asyncComponent/media/sw-media-base-item'));
    SnapAdmin.Component.extend('sw-media-compact-upload-v2', 'sw-media-upload-v2', () => import('src/app/asyncComponent/media/sw-media-compact-upload-v2'));
    SnapAdmin.Component.register('sw-media-entity-mapper', () => import('src/app/asyncComponent/media/sw-media-entity-mapper'));
    SnapAdmin.Component.register('sw-media-field', () => import('src/app/asyncComponent/media/sw-media-field'));
    SnapAdmin.Component.register('sw-media-folder-content', () => import('src/app/asyncComponent/media/sw-media-folder-content'));
    SnapAdmin.Component.register('sw-media-folder-item', () => import('src/app/asyncComponent/media/sw-media-folder-item'));
    SnapAdmin.Component.register('sw-media-list-selection-item-v2', () => import('src/app/asyncComponent/media/sw-media-list-selection-item-v2'));
    SnapAdmin.Component.register('sw-media-list-selection-v2', () => import('src/app/asyncComponent/media/sw-media-list-selection-v2'));
    SnapAdmin.Component.register('sw-media-media-item', () => import('src/app/asyncComponent/media/sw-media-media-item'));
    SnapAdmin.Component.register('sw-media-modal-delete', () => import('src/app/asyncComponent/media/sw-media-modal-delete'));
    SnapAdmin.Component.register('sw-media-modal-folder-dissolve', () => import('src/app/asyncComponent/media/sw-media-modal-folder-dissolve'));
    SnapAdmin.Component.register('sw-media-modal-folder-settings', () => import('src/app/asyncComponent/media/sw-media-modal-folder-settings'));
    SnapAdmin.Component.register('sw-media-modal-move', () => import('src/app/asyncComponent/media/sw-media-modal-move'));
    SnapAdmin.Component.register('sw-media-modal-replace', () => import('src/app/asyncComponent/media/sw-media-modal-replace'));
    SnapAdmin.Component.register('sw-media-preview-v2', () => import('src/app/asyncComponent/media/sw-media-preview-v2'));
    SnapAdmin.Component.extend('sw-media-replace', 'sw-media-upload-v2', import('src/app/asyncComponent/media/sw-media-replace'));
    SnapAdmin.Component.register('sw-media-upload-v2', () => import('src/app/asyncComponent/media/sw-media-upload-v2'));
    SnapAdmin.Component.register('sw-media-url-form', () => import('src/app/asyncComponent/media/sw-media-url-form'));
    SnapAdmin.Component.register('sw-sidebar-media-item', () => import('src/app/asyncComponent/media/sw-sidebar-media-item'));
    SnapAdmin.Component.register('sw-extension-icon', () => import('src/app/asyncComponent/extension/sw-extension-icon'));
    SnapAdmin.Component.register('sw-ai-copilot-badge', () => import('src/app/asyncComponent/feedback/sw-ai-copilot-badge'));
    SnapAdmin.Component.register('sw-ai-copilot-warning', () => import('src/app/asyncComponent/feedback/sw-ai-copilot-warning'));
    /* eslint-enable sw-deprecation-rules/private-feature-declarations, max-len */
};
