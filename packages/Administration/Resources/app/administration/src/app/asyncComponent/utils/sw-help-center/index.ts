import template from './sw-help-center.html.twig';
import './sw-help-center.scss';

/**
 * @description Displays an icon and a link to the help sidebar
 *
 * @package buyers-experience
 *
 * @private
 */
export default SnapAdmin.Component.wrapComponentConfig({
    template,

    computed: {
        showHelpSidebar(): boolean {
            return SnapAdmin.State.get('adminHelpCenter').showHelpSidebar;
        },

        showShortcutModal(): boolean {
            return SnapAdmin.State.get('adminHelpCenter').showShortcutModal;
        },
    },

    watch: {
        showShortcutModal(value) {
            const shortcutModal = this.$refs.shortcutModal as { onOpenShortcutOverviewModal: () => void };

            if (!shortcutModal) {
                return;
            }

            if (value === false) {
                this.setFocusToSidebar();

                return;
            }

            shortcutModal.onOpenShortcutOverviewModal();
        },
    },

    methods: {
        openHelpSidebar(): void {
            SnapAdmin.State.commit('adminHelpCenter/setShowHelpSidebar', true);
        },

        openShortcutModal(): void {
            SnapAdmin.State.commit('adminHelpCenter/setShowShortcutModal', true);
        },

        closeShortcutModal(): void {
            SnapAdmin.State.commit('adminHelpCenter/setShowShortcutModal', false);
        },

        setFocusToSidebar(): void {
            const helpSidebar = this.$refs.helpSidebar as { setFocusToSidebar: () => void };

            if (!helpSidebar) {
                return;
            }

            helpSidebar.setFocusToSidebar();
        },
    },
});
