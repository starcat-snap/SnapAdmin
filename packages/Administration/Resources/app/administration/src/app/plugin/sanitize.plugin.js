/**
 * @package admin
 */

const { warn } = SnapAdmin.Utils.debug;
const Sanitizer = SnapAdmin.Helper.SanitizerHelper;

let pluginInstalled = false;

/**
 * @private
 */
export default {
    install(Vue) {
        if (pluginInstalled) {
            warn('Sanitize Plugin', 'This plugin is already installed');
            return false;
        }

        Vue.prototype.$sanitizer = Sanitizer;
        Vue.prototype.$sanitize = Sanitizer.sanitize;

        pluginInstalled = true;

        return true;
    },
};
