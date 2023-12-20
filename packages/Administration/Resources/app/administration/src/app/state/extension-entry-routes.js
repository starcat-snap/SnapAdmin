/**
 * @package admin
 *
 * @private
 */
export default {
    namespaced: true,
    state: {
        routes: {},
    },

    mutations: {
        addItem(state, config) {
            SnapAdmin.Application.view.setReactive(state.routes, config.extensionName, config);
        },
    },
};
