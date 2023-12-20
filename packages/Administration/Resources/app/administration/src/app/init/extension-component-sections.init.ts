/**
 * @package admin
 *
 * @private
 */
export default function initializeExtensionComponentSections(): void {
    // Handle incoming ExtensionComponentRenderer requests from the ExtensionAPI
    SnapAdmin.ExtensionAPI.handle('uiComponentSectionRenderer', (componentConfig) => {
        SnapAdmin.State.commit('extensionComponentSections/addSection', componentConfig);
    });
}
