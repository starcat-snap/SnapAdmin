import { ui } from '@snap-admin/admin-extension-sdk';
import initMainModules from 'src/app/init/main-module.init';

let stateDispatchBackup = null;

describe('src/app/init/main-module.init.ts', () => {
    beforeAll(() => {
        initMainModules();
        stateDispatchBackup = SnapAdmin.State.dispatch;
    });

    beforeEach(() => {
        Object.defineProperty(SnapAdmin.State, 'dispatch', {
            value: stateDispatchBackup,
            writable: true,
            configurable: true,
        });
        SnapAdmin.State.get('extensionSdkModules').modules = [];

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

    it('should init the main module handler', async () => {
        await ui.mainModule.addMainModule({
            heading: 'My awesome module',
            locationId: 'my-awesome-module',
            displaySearchBar: true,
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(1);
        expect(SnapAdmin.State.get('extensionSdkModules').modules[0]).toEqual({
            id: expect.any(String),
            baseUrl: '',
            heading: 'My awesome module',
            displaySearchBar: true,
            locationId: 'my-awesome-module',
        });
    });

    it('should not handle requests when extension is not valid', async () => {
        SnapAdmin.State._store.state.extensions = {};

        await expect(async () => {
            await ui.mainModule.addMainModule({
                heading: 'My awesome module',
                locationId: 'my-awesome-module',
                displaySearchBar: true,
            });
        }).rejects.toThrow(new Error('Extension with the origin "" not found.'));

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(0);
    });

    it('should not commit the extension when moduleID could not be generated', async () => {
        jest.spyOn(SnapAdmin.State, 'dispatch').mockImplementationOnce(() => {
            return Promise.resolve(null);
        });

        await ui.mainModule.addMainModule({
            heading: 'My awesome module',
            locationId: 'my-awesome-module',
            displaySearchBar: true,
        });

        expect(SnapAdmin.State.get('extensionSdkModules').modules).toHaveLength(0);
    });
});
