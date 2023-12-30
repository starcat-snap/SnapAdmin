import template from './sw-extension-icon-polyfill.html.twig';
import './sw-extension-icon-polyfill.scss';

/**
 * @deprecated tag:v3.0.0 - Will be removed. Use sw-extension-icon instead
 */
export default SnapAdmin.Component.wrapComponentConfig({
    template,

    props: {
        src: {
            type: String,
            required: true
        },
        alt: {
            type: String,
            required: false,
            default: ''
        }
    },

    computed: {
        hasCoreComponent() {
            return SnapAdmin.Component.getComponentRegistry().has('sw-extension-icon');
        }
    }
});
