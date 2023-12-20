import { defineComponent } from 'vue';

/**
 * @package services-settings
 * @private
 */
export default SnapAdmin.Mixin.register('sw-extension-error', defineComponent({
    mixins: [SnapAdmin.Mixin.getByName('notification')],

    methods: {
        showExtensionErrors(errorResponse) {
            SnapAdmin.Service('extensionErrorService')
                .handleErrorResponse(errorResponse, this)
                .forEach((notification) => {
                    this.createNotificationError(notification);
                });
        },
    },
}));
