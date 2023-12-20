/**
 * @package admin
 *
 * @private
 * @module core/factory/context
 * @param {Object} context
 * @type factory
 */
export default function createContext(context = {}) {
    // set initial context
    SnapAdmin.State.commit('context/setAppEnvironment', process.env.NODE_ENV);
    SnapAdmin.State.commit('context/setAppFallbackLocale', 'en-GB');

    // assign unknown context information
    Object.entries(context).forEach(([key, value]) => {
        SnapAdmin.State.commit('context/addAppValue', { key, value });
    });

    return SnapAdmin.Context.app;
}
