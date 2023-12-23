import template from './sw-extension-app-module-error-page.html.twig';
import './sw-extension-app-module-error-page.scss';

/**
 * @package services-settings
 * @private
 */
export default SnapAdmin.Component.wrapComponentConfig({
    template,

    computed: {
        assetFilter() {
            return SnapAdmin.Filter.getByName('asset');
        },
    },

    methods: {
        goBack(): void {
            this.$router.go(-1);
        },
    },
});
