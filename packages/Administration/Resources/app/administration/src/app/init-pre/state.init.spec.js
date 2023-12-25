import initState from 'src/app/init-pre/state.init';

describe('src/app/init-pre/state.init.ts', () => {
    initState();

    it('should contain all state methods', () => {
        expect(SnapAdmin.State._store).toBeDefined();
        expect(SnapAdmin.State.list).toBeDefined();
        expect(SnapAdmin.State.get).toBeDefined();
        expect(SnapAdmin.State.getters).toBeDefined();
        expect(SnapAdmin.State.commit).toBeDefined();
        expect(SnapAdmin.State.dispatch).toBeDefined();
        expect(SnapAdmin.State.watch).toBeDefined();
        expect(SnapAdmin.State.subscribe).toBeDefined();
        expect(SnapAdmin.State.subscribeAction).toBeDefined();
        expect(SnapAdmin.State.registerModule).toBeDefined();
        expect(SnapAdmin.State.unregisterModule).toBeDefined();
    });

    it('should initialized all state modules', () => {
        expect(SnapAdmin.State.list()).toHaveLength(23);

        expect(SnapAdmin.State.get('notification')).toBeDefined();
        expect(SnapAdmin.State.get('session')).toBeDefined();
        expect(SnapAdmin.State.get('system')).toBeDefined();
        expect(SnapAdmin.State.get('adminMenu')).toBeDefined();
        expect(SnapAdmin.State.get('licenseViolation')).toBeDefined();
        expect(SnapAdmin.State.get('context')).toBeDefined();
        expect(SnapAdmin.State.get('error')).toBeDefined();
        expect(SnapAdmin.State.get('settingsItems')).toBeDefined();
        expect(SnapAdmin.State.get('snapAdminApps')).toBeDefined();
        expect(SnapAdmin.State.get('extensionEntryRoutes')).toBeDefined();
        expect(SnapAdmin.State.get('marketing')).toBeDefined();
        expect(SnapAdmin.State.get('extensionComponentSections')).toBeDefined();
        expect(SnapAdmin.State.get('extensions')).toBeDefined();
        expect(SnapAdmin.State.get('tabs')).toBeDefined();
        expect(SnapAdmin.State.get('menuItem')).toBeDefined();
        expect(SnapAdmin.State.get('extensionSdkModules')).toBeDefined();
        expect(SnapAdmin.State.get('modals')).toBeDefined();
        expect(SnapAdmin.State.get('extensionMainModules')).toBeDefined();
        expect(SnapAdmin.State.get('actionButtons')).toBeDefined();
        expect(SnapAdmin.State.get('ruleConditionsConfig')).toBeDefined();
        expect(SnapAdmin.State.get('sdkLocation')).toBeDefined();
        expect(SnapAdmin.State.get('usageData')).toBeDefined();
    });
});
