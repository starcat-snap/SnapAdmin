/**
 * @package admin
 *
 * @private
 */
export default function initializeActions(): void {
    SnapAdmin.ExtensionAPI.handle('actionExecute', async (actionConfiguration, additionalInformation) => {
        const extensionName = Object.keys(SnapAdmin.State.get('extensions'))
            .find(key => SnapAdmin.State.get('extensions')[key].baseUrl.startsWith(additionalInformation._event_.origin));

        if (!extensionName) {
            // eslint-disable-next-line max-len
            throw new Error(`Could not find an extension with the given event origin "${additionalInformation._event_.origin}"`);
        }

        await SnapAdmin.Service('extensionSdkService').runAction(
            {
                url: actionConfiguration.url,
                entity: actionConfiguration.entity,
                action: SnapAdmin.Utils.createId(),
                appName: extensionName,
            },
            actionConfiguration.entityIds,
        );
    });
}
