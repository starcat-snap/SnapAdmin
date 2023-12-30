import template from './sw-settings-admin-updates-index.html.twig';
import './sw-settings-admin-updates-index.scss';

const { Component, Mixin } = SnapAdmin;

/**
 * @package services-settings
 * @private
 */
Component.register('sw-settings-admin-updates-index', {
    template,

    inject: ['updateService'],
    mixins: [
        Mixin.getByName('notification'),
    ],
    data() {
        return {
            isLoading: false,
            isSaveSuccessful: false,
            isSearchingForUpdates: false,
            updateModalShown: false,
            updateInfo: null,
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle(),
        };
    },

    computed: {
        adminVersion() {
            return SnapAdmin.Context.app.config.version;
        },
    },

    methods: {
        searchForUpdates() {
            this.isSearchingForUpdates = true;
            this.updateService.checkForUpdates().then(response => {
                this.isSearchingForUpdates = false;

                if (response.version) {
                    this.updateInfo = response;
                    this.updateModalShown = true;
                } else {
                    this.createNotificationInfo({
                        message: this.$tc('sw-settings-admin-updates.notifications.alreadyUpToDate'),
                    });
                }
            });
        },

        openUpdateWizard() {
            this.updateModalShown = false;

            this.$nextTick(() => {
                this.$router.push({ name: 'sw.settings.admin.updates.wizard' });
            });
        },

        saveFinish() {
            this.isSaveSuccessful = false;
        },

        onSave() {
            this.isSaveSuccessful = false;
            this.isLoading = true;

            this.$refs.systemConfig.saveAll().then(() => {
                this.isLoading = false;
                this.isSaveSuccessful = true;
            }).catch((err) => {
                this.isLoading = false;
                this.createNotificationError({
                    message: err,
                });
            });
        },

        onLoadingChanged(loading) {
            this.isLoading = loading;
        },
    },
});
