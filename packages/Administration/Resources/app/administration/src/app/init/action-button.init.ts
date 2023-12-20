/**
 * @package admin
 *
 * @private
 */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function initializeActionButtons(): void {
    SnapAdmin.ExtensionAPI.handle('actionButtonAdd', (configuration) => {
        SnapAdmin.State.commit('actionButtons/add', configuration);
    });
}
