/**
 * @package admin
 *
 * @private
 */
export default function initializeModal(): void {
    // eslint-disable-next-line @typescript-eslint/require-await
    SnapAdmin.ExtensionAPI.handle('uiModalOpen', async (modalConfig, { _event_ }) => {
        const extension = Object.values(SnapAdmin.State.get('extensions'))
            .find(ext => ext.baseUrl.startsWith(_event_.origin));

        if (!extension) {
            throw new Error(`Extension with the origin "${_event_.origin}" not found.`);
        }

        SnapAdmin.State.commit('modals/openModal', {
            closable: true,
            showHeader: true,
            variant: 'default',
            baseUrl: extension.baseUrl,
            ...modalConfig,
        });
    });

    SnapAdmin.ExtensionAPI.handle('uiModalClose', ({ locationId }) => {
        SnapAdmin.State.commit('modals/closeModal', locationId);
    });
}
