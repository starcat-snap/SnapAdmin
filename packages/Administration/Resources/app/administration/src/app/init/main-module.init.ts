/**
 * @package admin
 *
 * @private
 */
export default function initMainModules(): void {
    SnapAdmin.ExtensionAPI.handle('mainModuleAdd', async (mainModuleConfig, additionalInformation) => {
        const extensionName = Object.keys(SnapAdmin.State.get('extensions'))
            .find(key => SnapAdmin.State.get('extensions')[key].baseUrl.startsWith(additionalInformation._event_.origin));

        if (!extensionName) {
            throw new Error(`Extension with the origin "${additionalInformation._event_.origin}" not found.`);
        }

        const extension = SnapAdmin.State.get('extensions')?.[extensionName];

        await SnapAdmin.State.dispatch('extensionSdkModules/addModule', {
            heading: mainModuleConfig.heading,
            locationId: mainModuleConfig.locationId,
            displaySearchBar: mainModuleConfig.displaySearchBar ?? true,
            baseUrl: extension.baseUrl,
        }).then((moduleId) => {
            if (typeof moduleId !== 'string') {
                return;
            }

            SnapAdmin.State.commit('extensionMainModules/addMainModule', {
                extensionName,
                moduleId,
            });
        });
    });

    SnapAdmin.ExtensionAPI.handle('smartBarButtonAdd', (configuration) => {
        SnapAdmin.State.commit('extensionSdkModules/addSmartBarButton', configuration);
    });
}
