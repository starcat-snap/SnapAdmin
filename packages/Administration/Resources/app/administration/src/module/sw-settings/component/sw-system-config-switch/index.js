/**
 * @package buyers-experience
 */
import template from './sw-system-config-switch.html.twig';

const {Component} = SnapAdmin;
const {debug} = SnapAdmin.Utils;

/**
 * @private
 * @description
 * Renders a sales channel switcher.
 * @status ready
 * @example-type code-only
 * @component-example
 * <sw-sales-channel-switch></sw-sales-channel-switch>
 */
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
Component.register('sw-system-config-switch', {
    template,

    props: {
        disabled: {
            type: Boolean,
            required: false,
            default: false,
        },
        scope: {
            type: String,
            required: true,
        },
        // FIXME: add default value
        // eslint-disable-next-line vue/require-default-prop
        abortChangeFunction: {
            type: Function,
            required: false,
        },
        // FIXME: add default value
        // eslint-disable-next-line vue/require-default-prop
        saveChangesFunction: {
            type: Function,
            required: false,
        },
        label: {
            type: String,
            required: false,
            default: '',
        },
    },

    data() {
        return {
            scopeId: '',
            lastScopeId: '',
            newScopeId: '',
            showUnsavedChangesModal: false,
        };
    },

    methods: {
        onChange(id) {
            this.scopeId = id;
            this.newScopeId = id;

            this.checkAbort();
        },
        checkAbort() {
            // Check if abort function exists und reset the select field if the change should be aborted
            if (typeof this.abortChangeFunction === 'function') {
                if (this.abortChangeFunction({
                    oldScopeId: this.lastScopeId,
                    newScopeId: this.scopeId,
                })) {
                    this.showUnsavedChangesModal = true;
                    this.scopeId = this.lastScopeId;
                    this.$refs.scopeSelect.loadSelected();
                    return;
                }
            }

            this.emitChange();
        },
        emitChange() {
            this.lastScopeId = this.scopeId;

            this.$emit('change-scope-id', this.scopeId);
        },
        onCloseChangesModal() {
            this.showUnsavedChangesModal = false;
            this.newScopeId = '';
        },
        onClickSaveChanges() {
            let save = {};
            // Check if save function exists and wait for it before changing the salesChannel
            if (typeof this.saveChangesFunction === 'function') {
                save = this.saveChangesFunction();
            } else {
                debug.warn('sw-system-config-switch', 'You need to implement an own save function to save the changes!');
            }
            return Promise.resolve(save).then(() => {
                this.changeToNewScope();
                this.onCloseChangesModal();
            });
        },
        onClickRevertUnsavedChanges() {
            this.changeToNewScope();
            this.onCloseChangesModal();
        },
        changeToNewScope(scopeId) {
            if (scopeId) {
                this.newScopeId = scopeId;
            }
            this.scopeId = this.newScopeId;
            this.newScopeId = '';
            this.$refs.scopeSelect.loadSelected();
            this.emitChange();
        },
    },
});
