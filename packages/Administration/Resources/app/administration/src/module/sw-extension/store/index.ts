import type { SnapAdminClass } from 'src/core/shopware';
import extensionStore from './extensions.store';

/**
 * @package services-settings
 * @private
 */
export default function initState(SnapAdmin: SnapAdminClass): void {
    SnapAdmin.State.registerModule('shopwareExtensions', extensionStore);

    let languageId = SnapAdmin.State.get('session').languageId;
    SnapAdmin.State._store.subscribe(async ({ type }, state): Promise<void> => {
        if (!SnapAdmin.Service('acl').can('system.plugin_maintain')) {
            return;
        }

        if (type === 'setAdminLocale' && state.session.languageId !== '' && languageId !== state.session.languageId) {
            // Always on page load setAdminLocale will be called once. Catch it to not load refresh extensions
            if (languageId === '') {
                languageId = state.session.languageId;
                return;
            }

            languageId = state.session.languageId;
            await SnapAdmin.Service('shopwareExtensionService').updateExtensionData().then();
        }
    });
}
