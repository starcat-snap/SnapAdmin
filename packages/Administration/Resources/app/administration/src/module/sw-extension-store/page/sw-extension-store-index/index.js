import template from './sw-extension-store-index.html.twig';
import './sw-extension-store-index.scss';

/**
 * @private
 */
export default {
    template,

    inject: ['extensionStoreActionService', 'snapAdminExtensionService'],

    props: {
        id: {
            type: String,
            required: false,
            default: null,
        },
    },

    data() {
        return {
            isAvailable: false,
            failReason: '',
            listingError: null,
            isLoading: false,
        };
    },

    computed: {
        storeSearchKey() {
            return this.$route.name;
        },

        activeFilters() {
            return SnapAdmin.State.get('snapAdminExtensions').search.filter;
        },

        searchValue() {
            return SnapAdmin.State.get('snapAdminExtensions').search.term;
        },

        isTheme() {
            const isTheme = this.$route.name.includes('theme');

            return isTheme ? 'themes' : 'apps';
        },
    },

    watch: {
        isTheme: {
            immediate: true,
            handler(newValue) {
                SnapAdmin.State.commit('snapAdminExtensions/setSearchValue', { key: 'page', value: 1 });
                this.$set(this.activeFilters, 'group', newValue);
            },
        },
    },

    created() {
        this.createdComponent();
    },

    methods: {
        createdComponent() {
            this.checkStoreUpdates();
        },

        async checkStoreUpdates() {
            this.isLoading = true;
            this.snapAdminExtensionService.updateExtensionData();
            this.isAvailable = true;
            this.isLoading = false;
        },

        onExtensionListingError(e) {
            const errors = SnapAdmin.Service('extensionErrorService').handleErrorResponse(e, this);

            this.isAvailable = false;
            this.listingError = errors && errors[0];
            this.failReason = 'listing_error';
        },

        isUpdateable(extension) {
            if (!extension || extension.latestVersion === null) {
                return false;
            }

            return extension.latestVersion !== extension.version;
        },

        updateSearch(term) {
            SnapAdmin.State.commit('snapAdminExtensions/setSearchValue', { key: 'term', value: term });
        },
    },
};
