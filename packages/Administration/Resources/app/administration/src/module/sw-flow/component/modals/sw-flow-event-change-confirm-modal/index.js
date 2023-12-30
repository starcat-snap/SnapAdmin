import template from './sw-flow-event-change-confirm-modal.html.twig';
import './sw-flow-event-change-confirm-modal.scss';

const { Component, State } = SnapAdmin;
const { EntityCollection } = SnapAdmin.Data;
const { mapGetters } = Component.getComponentHelper();

/**
 * @private
 * @package services-settings
 */
export default {
    template,

    computed: {
        ...mapGetters('swFlowState', ['sequences']),
    },

    methods: {
        onConfirm() {
            const sequencesCollection = new EntityCollection(
                this.sequences.source,
                this.sequences.entity,
                SnapAdmin.Context.api,
                null,
                [],
            );

            State.commit('swFlowState/setSequences', sequencesCollection);

            this.$emit('modal-confirm');
            this.onClose();
        },

        onClose() {
            this.$emit('modal-close');
        },
    },
};
