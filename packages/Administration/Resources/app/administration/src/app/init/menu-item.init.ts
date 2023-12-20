/**
 * @package admin
 *
 * @private
 */
export default function initMenuItems(): void {
    SnapAdmin.ExtensionAPI.handle('menuItemAdd', async (menuItemConfig, additionalInformation) => {
        const extension = Object.values(SnapAdmin.State.get('extensions'))
            .find(ext => ext.baseUrl.startsWith(additionalInformation._event_.origin));

        if (!extension) {
            throw new Error(`Extension with the origin "${additionalInformation._event_.origin}" not found.`);
        }

        await SnapAdmin.State.dispatch('extensionSdkModules/addModule', {
            heading: menuItemConfig.label,
            locationId: menuItemConfig.locationId,
            displaySearchBar: menuItemConfig.displaySearchBar,
            baseUrl: extension.baseUrl,
        }).then((moduleId) => {
            if (typeof moduleId !== 'string') {
                return;
            }

            SnapAdmin.State.commit('menuItem/addMenuItem', {
                ...menuItemConfig,
                moduleId,
            });
        });
    });
}
