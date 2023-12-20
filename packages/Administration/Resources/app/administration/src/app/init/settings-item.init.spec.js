import initializeSettingItems from 'src/app/init/settings-item.init';
import { ui } from '@snap-admin/admin-extension-sdk';

let stateDispatchBackup = null;
describe('src/app/init/settings-item.init.ts', () => {
    beforeAll(() => {
        initializeSettingItems();
        stateDispatchBackup = SnapAdmin.State.dispatch;
    });

    beforeEach(() => {
        Object.defineProperty(SnapAdmin.State, 'dispatch', {
            value: stateDispatchBackup,
            writable: true,
            configurable: true,
        });
        SnapAdmin.State.get('extensionSdkModules').modules = [];
        SnapAdmin.State.get('settingsItems').settingsGroups = {
            shop: [],
            system: [],
            plugins: [],
        };

        SnapAdmin.State._store.state.extensions = {};
        SnapAdmin.State.commit('extensions/addExtension', {
            name: 'jestapp',
            baseUrl: '',
            permissions: [],
            version: '1.0.0',
            type: 'app',
            integrationId: '123',
            active: true,
        });
    });

    it('should handle the settingsItemAdd requests', async () => {
        await ui.settings.addSettingsItem({
            label: 'App Settings',
            locationId: 'settings-location-id',
            icon: 'default-object-books',
            displaySearchBar: true,
            tab: 'system',
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(1);
        expect(SnapAdmin.State.get('extensionSdkModules').modules[0]).toEqual({
            baseUrl: '',
            displaySearchBar: true,
            heading: 'App Settings',
            id: expect.any(String),
            locationId: 'settings-location-id',
        });

        expect(SnapAdmin.State.get('settingsItems').settingsGroups.system).toHaveLength(1);
        expect(SnapAdmin.State.get('settingsItems').settingsGroups.system[0]).toEqual({
            group: 'system',
            icon: 'default-object-books',
            id: 'settings-location-id',
            label: {
                label: 'App Settings',
                translated: true,
            },
            name: 'settings-location-id',
            to: {
                name: 'sw.extension.sdk.index',
                params: {
                    id: expect.any(String),
                },
            },
        });
    });

    it('should handle the settingsItemAdd requests with fallback', async () => {
        await ui.settings.addSettingsItem({
            label: 'App Settings',
            locationId: 'settings-location-id',
            icon: 'default-object-books',
            displaySearchBar: true,
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(1);
        expect(SnapAdmin.State.get('extensionSdkModules').modules[0]).toEqual({
            baseUrl: '',
            displaySearchBar: true,
            heading: 'App Settings',
            id: expect.any(String),
            locationId: 'settings-location-id',
        });

        expect(SnapAdmin.State.get('settingsItems').settingsGroups.plugins).toHaveLength(1);
        expect(SnapAdmin.State.get('settingsItems').settingsGroups.plugins[0]).toEqual({
            group: 'plugins',
            icon: 'default-object-books',
            id: 'settings-location-id',
            label: {
                label: 'App Settings',
                translated: true,
            },
            name: 'settings-location-id',
            to: {
                name: 'sw.extension.sdk.index',
                params: {
                    id: expect.any(String),
                },
            },
        });
    });

    it('should handle the settingsItemAdd requests with unallowed tab', async () => {
        await ui.settings.addSettingsItem({
            label: 'App Settings',
            locationId: 'settings-location-id',
            icon: 'default-object-books',
            displaySearchBar: true,
            tab: 'not-allowed',
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(1);
        expect(SnapAdmin.State.get('extensionSdkModules').modules[0]).toEqual({
            baseUrl: '',
            displaySearchBar: true,
            heading: 'App Settings',
            id: expect.any(String),
            locationId: 'settings-location-id',
        });

        expect(SnapAdmin.State.get('settingsItems').settingsGroups.plugins).toHaveLength(1);
        expect(SnapAdmin.State.get('settingsItems').settingsGroups.plugins[0]).toEqual({
            group: 'plugins',
            icon: 'default-object-books',
            id: 'settings-location-id',
            label: {
                label: 'App Settings',
                translated: true,
            },
            name: 'settings-location-id',
            to: {
                name: 'sw.extension.sdk.index',
                params: {
                    id: expect.any(String),
                },
            },
        });
    });

    it('should not handle requests when extension is not valid', async () => {
        SnapAdmin.State._store.state.extensions = {};

        await expect(async () => {
            await ui.settings.addSettingsItem({
                label: 'App Settings',
                locationId: 'settings-location-id',
                icon: 'default-object-books',
                displaySearchBar: true,
                tab: 'plugins',
            });
        }).rejects.toThrow(new Error('Extension with the origin "" not found.'));

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(0);
    });

    it('should not commit the extension when moduleID could not be generated', async () => {
        jest.spyOn(SnapAdmin.State, 'dispatch').mockImplementationOnce(() => {
            return Promise.resolve(null);
        });

        await ui.settings.addSettingsItem({
            label: 'App Settings',
            locationId: 'settings-location-id',
            icon: 'default-object-books',
            displaySearchBar: true,
            tab: 'plugins',
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(0);
    });
});
