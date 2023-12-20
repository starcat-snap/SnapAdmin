import type { Module } from 'vuex';
import type { UserInfo } from 'src/core/service/api/store.api.service';

interface SnapAdminExtensionsState {
    search: {
        page: number,
        limit: number,
        rating: $TSFixMe,
        sorting: $TSFixMe,
        term: null|string,
        filter: $TSFixMe,
    }
    categoriesLanguageId: string|null,
    myExtensions: {
        loading: boolean,
    }
    userInfo: UserInfo|null,
}

const snapAdminExtensionsStore: Module<SnapAdminExtensionsState, VuexRootState> = {
    namespaced: true,

    state() {
        return {
            search: {
                page: 1,
                limit: 12,
                rating: null,
                sorting: null,
                term: null,
                filter: {},
            },
            extensionListing: [],
            categoriesLanguageId: null,
            myExtensions: {
                loading: true,
                data: [],
            },
            userInfo: null,
            snapAdminId: null,
            loginStatus: false,
            licensedExtensions: {
                loading: false,
                data: [],
            },
            totalPlugins: 0,
            plugins: null,
        };
    },

    mutations: {
        loadMyExtensions(state) {
            state.myExtensions.loading = true;
        },

        // eslint-disable-next-line @typescript-eslint/no-inferrable-types
        setLoading(state, value: boolean = true) {
            state.myExtensions.loading = value;
        },
        categoriesLanguageId(state, languageId: string) {
            state.categoriesLanguageId = languageId;
        },

        setUserInfo(state, userInfo: UserInfo|null) {
            state.userInfo = userInfo;
        },

        pluginErrorsMapped() { /* nth */ },
    },
};

/**
 * @package services-settings
 * @private
 */
export default snapAdminExtensionsStore;

/**
 * @private
 */
export type { SnapAdminExtensionsState };
