/**
 * @private
 */
export default function initializeSettingItems(): void {
    SnapAdmin.ExtensionAPI.handle('settingsItemAdd', async (settingsItemConfig, additionalInformation) => {
        const extension = Object.values(SnapAdmin.State.get('extensions'))
            .find(ext => ext.baseUrl.startsWith(additionalInformation._event_.origin));

        if (!extension) {
            throw new Error(`Extension with the origin "${additionalInformation._event_.origin}" not found.`);
        }

        let group = 'plugins';

        if (!settingsItemConfig.tab) {
            settingsItemConfig.tab = 'plugins';
        }

        group = settingsItemConfig.tab;

        await SnapAdmin.State.dispatch('extensionSdkModules/addModule', {
            heading: settingsItemConfig.label,
            locationId: settingsItemConfig.locationId,
            displaySearchBar: settingsItemConfig.displaySearchBar,
            baseUrl: extension.baseUrl,
        }).then(moduleId => {
            if (typeof moduleId !== 'string') {
                return;
            }

            SnapAdmin.State.commit('settingsItems/addItem', {
                group: group,
                icon: settingsItemConfig.icon,
                id: settingsItemConfig.locationId,
                label: {
                    translated: true,
                    label: settingsItemConfig.label,
                },
                name: settingsItemConfig.locationId,
                to: {
                    name: 'sw.extension.sdk.index',
                    params: {
                        id: moduleId,
                    },
                },
            });
        });
    });
}
