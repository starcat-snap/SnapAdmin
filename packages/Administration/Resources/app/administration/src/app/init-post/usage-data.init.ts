/**
 * @package data-services
 *
 * @private
 */
export default function initUsageData(): Promise<void> {
    return new Promise<void>((resolve) => {
        const loginService = SnapAdmin.Service('loginService');
        const usageDataApiService = SnapAdmin.Service('usageDataService');

        if (!loginService.isLoggedIn()) {
            SnapAdmin.State.commit('usageData/resetConsent');

            resolve();

            return;
        }

        usageDataApiService.getConsent().then((usageData) => {
            SnapAdmin.State.commit('usageData/updateConsent', usageData);
        }).catch(() => {
            SnapAdmin.State.commit('usageData/resetConsent');
        }).finally(() => {
            resolve();
        });
    });
}
