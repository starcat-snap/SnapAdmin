import template from './sw-extension-store-listing.html.twig';
import './sw-extension-store-listing.scss';

/**
 * @private
 */
export default {
    name: 'sw-extension-store-listing',
    template,

    inject: ['feature'],

    mixins: ['sw-extension-error'],

    data() {
        return {
            isLoading: false,
        };
    },

    computed: {
        extensions() {
            return SnapAdmin.State.get('snapAdminExtensions').extensionListing;
        },

        currentSearch() {
            return SnapAdmin.State.get('snapAdminExtensions').search;
        },

        page() {
            return this.currentSearch.page;
        },

        limit() {
            return this.currentSearch.limit;
        },

        total() {
            return this.extensions.total || 0;
        },

        rating() {
            return this.currentSearch.rating;
        },

        languageId() {
            return SnapAdmin.State.get('session').languageId;
        },

        assetFilter() {
            return SnapAdmin.Filter.getByName('asset');
        },

        currentLocale() {
            return SnapAdmin.State.get('session').currentLocale === 'en-US' ? 'en' : 'zh';
        },
    },

    watch: {
        currentSearch: {
            deep: true,
            immediate: true,
            handler() {
                this.getList();
            },
        },
        languageId(newValue) {
            if (newValue !== '') {
                this.getList();
            }
        },
    },

    methods: {
        async getList() {
            this.isLoading = true;

            if (this.languageId === '') {
                return;
            }

            try {
                await this.search();
            } catch (e) {
                this.showExtensionErrors(e);
                this.$emit('extension-listing-errors', e);
            } finally {
                this.isLoading = false;
            }
        },

        async search() {
            const extensionDataService = SnapAdmin.Service('extensionStoreDataService');

            const page = await extensionDataService.getExtensionList(
                SnapAdmin.State.get('snapAdminExtensions').search,
                { ...SnapAdmin.Context.api, languageId: SnapAdmin.State.get('session').languageId },
            );

            SnapAdmin.State.commit('snapAdminExtensions/setExtensionListing', page);
        },

        setPage({ limit, page }) {
            SnapAdmin.State.commit('snapAdminExtensions/setSearchValue', { key: 'limit', value: limit });
            SnapAdmin.State.commit('snapAdminExtensions/setSearchValue', { key: 'page', value: page });
        },
    },
};
