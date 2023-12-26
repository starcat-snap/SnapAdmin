const { Application } = SnapAdmin;

/**
 * @package admin
 *
 * @module core/service/snap-admin-updates-listener
 */

/**
 *
 * @memberOf module:core/service/snap-admin-updates-listener
 * @method addSnapAdminUpdatesListener
 * @param loginService
 * @param serviceContainer
 */
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function addSnapAdminUpdatesListener(loginService, serviceContainer) {
    /** @var {String} localStorage token */
    let applicationRoot = null;

    loginService.addOnLoginListener(() => {
        if (!SnapAdmin.Service('acl').can('system.core_update')) {
            return;
        }

        serviceContainer.updateService.checkForUpdates()
            .then((response) => {
                if (response.version) {
                    createUpdatesAvailableNotification(response);
                }
            })
            .catch();
    });

    function createUpdatesAvailableNotification(response) {
        const cancelLabel =
            getApplicationRootReference().$tc('global.default.cancel');
        const updateLabel =
            getApplicationRootReference().$tc('global.notification-center.snap-admin-updates-listener.updateNow');

        const notification = {
            title: getApplicationRootReference().$t(
                'global.notification-center.snap-admin-updates-listener.updatesAvailableTitle',
                {
                    version: response.version,
                },
            ),
            message: getApplicationRootReference().$t(
                'global.notification-center.snap-admin-updates-listener.updatesAvailableMessage',
                {
                    version: response.version,
                },
            ),
            variant: 'info',
            growl: true,
            system: true,
            actions: [{
                label: updateLabel,
                route: { name: 'sw.settings.shopware.updates.wizard' },
            }, {
                label: cancelLabel,
            }],
            autoClose: false,
        };

        getApplicationRootReference().$store.dispatch(
            'notification/createNotification',
            notification,
        );
    }

    function getApplicationRootReference() {
        if (!applicationRoot) {
            applicationRoot = Application.getApplicationRoot();
        }

        return applicationRoot;
    }
}
