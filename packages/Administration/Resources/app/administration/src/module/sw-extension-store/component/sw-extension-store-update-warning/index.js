import template from './sw-extension-store-update-warning.html.twig';
import './sw-extension-store-update-warning.scss';

const { Mixin } = SnapAdmin;

/**
 * @private
 */
export default {
    template,

    inject: [
        'snapAdminExtensionService',
        'extensionStoreActionService',
        'cacheApiService'
    ],

    mixins: [
        Mixin.getByName('notification')
    ],

    data() {
        return {
            isUpdating: false
        };
    },

    computed: {},

    methods: {
        async updateExtension() {
            this.isUpdating = true;

            try {
                await this.extensionStoreActionService.downloadExtension('SwagExtensionStore');
                await this.snapAdminExtensionService.updateExtension('SwagExtensionStore', 'plugin');
                await this.clearCacheAndReloadPage();
            } catch (e) {
                this.isUpdating = false;

                SnapAdmin.Utils.debug.error(e);
                this.createNotificationError({
                    message: this.$tc(
                        'global.notification.unspecifiedSaveErrorMessage'
                    )
                });
            }
        },

        clearCacheAndReloadPage() {
            return this.cacheApiService.clear().then(() => {
                window.location.reload();
            });
        }
    }
};
