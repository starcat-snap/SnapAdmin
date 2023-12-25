import createHttpClient from 'src/core/factory/http.factory';
import createLoginService from 'src/core/service/login.service';
import StoreApiService from 'src/core/service/api/store.api.service';
import SnapAdminExtensionService from 'src/module/sw-extension/service/snap-admin-extension.service';
import ExtensionStoreActionService from 'src/module/sw-extension/service/extension-store-action.service';
import 'src/module/sw-extension/service';
import initState from 'src/module/sw-extension/store';
import appModulesFixtures from '../../../app/service/_mocks/testApps.json';

jest.mock('src/module/sw-extension/service/extension-store-action.service');
jest.mock('src/core/service/api/app-modules.service');

const httpClient = createHttpClient(SnapAdmin.Context.api);
SnapAdmin.Application.getContainer('init').httpClient = httpClient;
SnapAdmin.Service().register('loginService', () => {
    return createLoginService(httpClient, SnapAdmin.Context.api);
});

SnapAdmin.Service().register('storeService', () => {
    return new StoreApiService(httpClient, SnapAdmin.Service('loginService'));
});

SnapAdmin.Service().register('shopwareDiscountCampaignService', () => {
    return { isDiscountCampaignActive: jest.fn(() => true) };
});

/**
 * @package services-settings
 */
describe('src/module/sw-extension/service/shopware-extension.service', () => {
    let snapAdminExtensionService;

    beforeAll(() => {
        snapAdminExtensionService = SnapAdmin.Service('snapAdminExtensionService');

        initState(SnapAdmin);

        if (SnapAdmin.State.get('extensionEntryRoutes')) {
            SnapAdmin.State.unregisterModule('extensionEntryRoutes');
        }
        SnapAdmin.State.registerModule('extensionEntryRoutes', {
            namespaced: true,
            state: {
                routes: {
                    ExamplePlugin: {
                        route: 'test.foo',
                    },
                },
            },
        });
    });

    describe('it delegates lifecycle methods', () => {
        const mockedExtensionStoreActionService = new ExtensionStoreActionService(httpClient, SnapAdmin.Service('loginService'));
        mockedExtensionStoreActionService.getMyExtensions.mockImplementation(() => {
            return ['new extensions'];
        });

        const mockedSnapAdminExtensionService = new SnapAdminExtensionService(
            mockedExtensionStoreActionService,
            SnapAdmin.Service('shopwareDiscountCampaignService'),
            SnapAdmin.Service('storeService'),
        );

        function expectUpdateExtensionDataCalled() {
            expect(mockedExtensionStoreActionService.refresh).toHaveBeenCalledTimes(1);
            expect(mockedExtensionStoreActionService.getMyExtensions).toHaveBeenCalledTimes(1);

            expect(SnapAdmin.State.get('snapAdminExtensions').myExtensions.data)
                .toEqual(['new extensions']);
            expect(SnapAdmin.State.get('snapAdminExtensions').myExtensions.loading)
                .toBe(false);
        }

        beforeEach(() => {
            SnapAdmin.State.commit('snapAdminExtensions/myExtensions', []);
            SnapAdmin.State.commit('snapAdminApps/setApps', []);
        });

        it.each([
            ['installExtension', ['someExtension', 'app']],
            ['updateExtension', ['someExtension', 'app', true]],
            ['uninstallExtension', ['someExtension', 'app', true]],
            ['removeExtension', ['someExtension', 'app']],
        ])('delegates %s correctly', async (lifecycleMethod, parameters) => {
            await mockedSnapAdminExtensionService[lifecycleMethod](...parameters);

            expect(mockedExtensionStoreActionService[lifecycleMethod]).toHaveBeenCalledTimes(1);
            expect(mockedExtensionStoreActionService[lifecycleMethod]).toHaveBeenCalledWith(...parameters);

            expectUpdateExtensionDataCalled();
        });

        it('delegates cancelLicense correctly', async () => {
            await mockedSnapAdminExtensionService.cancelLicense(5);

            expect(mockedExtensionStoreActionService.cancelLicense).toHaveBeenCalledTimes(1);
            expect(mockedExtensionStoreActionService.cancelLicense).toHaveBeenCalledWith(5);
        });

        it.each([
            ['activateExtension'],
            ['deactivateExtension'],
        ])('delegates %s correctly', async (lifecycleMethod) => {
            await mockedSnapAdminExtensionService[lifecycleMethod]('someExtension', 'app');

            expect(mockedExtensionStoreActionService[lifecycleMethod]).toHaveBeenCalledTimes(1);
            expect(mockedExtensionStoreActionService[lifecycleMethod]).toHaveBeenCalledWith('someExtension', 'app');
        });
    });

    describe('checkLogin', () => {
        const checkLoginSpy = jest.spyOn(SnapAdmin.Service('storeService'), 'checkLogin');

        beforeEach(() => {
            SnapAdmin.State.commit('snapAdminExtensions/setUserInfo', true);
        });

        it.each([
            [{ userInfo: { email: 'user@snapadmin.net' } }],
            [{ userInfo: null }],
        ])('sets login status depending on checkLogin response', async (loginResponse) => {
            checkLoginSpy.mockImplementationOnce(() => loginResponse);

            await snapAdminExtensionService.checkLogin();

            expect(SnapAdmin.State.get('snapAdminExtensions').userInfo).toStrictEqual(loginResponse.userInfo);
        });

        it('sets login status to false if checkLogin request fails', async () => {
            checkLoginSpy.mockImplementationOnce(() => {
                throw new Error('something went wrong');
            });

            await snapAdminExtensionService.checkLogin();

            expect(SnapAdmin.State.get('snapAdminExtensions').loginStatus).toBe(false);
            expect(SnapAdmin.State.get('snapAdminExtensions').userInfo).toBeNull();
        });
    });

    describe('isVariantDiscounted', () => {
        it('returns true if price is discounted and campaign is active', async () => {
            const variant = {
                netPrice: 100,
                discountCampaign: {
                    discountedPrice: 80,
                },
            };

            expect(snapAdminExtensionService.isVariantDiscounted(variant)).toBe(true);
        });

        it('returns false if price is discounted but campaign is not active', async () => {
            const variant = {
                netPrice: 100,
                discountCampaign: {
                    discountedPrice: 80,
                },
            };

            SnapAdmin.Service('shopwareDiscountCampaignService')
                .isDiscountCampaignActive
                .mockImplementationOnce(() => false);

            expect(snapAdminExtensionService.isVariantDiscounted(variant)).toBe(false);
        });

        it('returns false if variant is falsy', async () => {
            expect(snapAdminExtensionService.isVariantDiscounted(null)).toBe(false);
        });

        it('returns false if variant has no discountCampaign', async () => {
            expect(snapAdminExtensionService.isVariantDiscounted({})).toBe(false);
        });

        it('returns false if discounted price is net price', async () => {
            expect(snapAdminExtensionService.isVariantDiscounted({
                netPrice: 100,
                discountCampaign: {
                    discountedPrice: 100,
                },
            })).toBe(false);
        });
    });

    describe('orderVariantsByRecommendation', () => {
        it('orders variants by recommendation and discounting', async () => {
            const variants = [
                { netPrice: 100, discountCampaign: { netPrice: 100 }, type: 'rent' },
                { netPrice: 100, discountCampaign: { netPrice: 80 }, type: 'test' },
                { netPrice: 100, discountCampaign: { netPrice: 100 }, type: 'test' },
                { netPrice: 100, discountCampaign: { netPrice: 100 }, type: 'buy' },
                { netPrice: 100, discountCampaign: { netPrice: 10 }, type: 'rent' },
            ];

            snapAdminExtensionService.orderVariantsByRecommendation(variants)
                .forEach((current, currentIndex, orderedVariants) => {
                    const isCurrentDiscounted = snapAdminExtensionService.isVariantDiscounted(current);
                    const currentRecommendation = snapAdminExtensionService.mapVariantToRecommendation(current);

                    orderedVariants.forEach((comparator, comparatorIndex) => {
                        const isComparatorDiscounted = snapAdminExtensionService.isVariantDiscounted(comparator);
                        const comparatorRecommendation = snapAdminExtensionService.mapVariantToRecommendation(comparator);

                        if (isCurrentDiscounted !== !isComparatorDiscounted) {
                            // discounted index is always smaller than undiscounted
                            if (isCurrentDiscounted && !isComparatorDiscounted) {
                                // eslint-disable-next-line jest/no-conditional-expect
                                expect(currentIndex).toBeLessThan(comparatorIndex);
                            }

                            if (!isCurrentDiscounted && isComparatorDiscounted) {
                                // eslint-disable-next-line jest/no-conditional-expect
                                expect(currentIndex).toBeGreaterThan(comparatorIndex);
                            }
                        } else {
                            // variants are ordered by recommendation
                            if (currentRecommendation < comparatorRecommendation) {
                                // eslint-disable-next-line jest/no-conditional-expect
                                expect(currentIndex).toBeLessThan(comparatorIndex);
                            }

                            if (currentIndex > comparatorRecommendation) {
                                // eslint-disable-next-line jest/no-conditional-expect
                                expect(currentIndex).toBeGreaterThan(comparatorIndex);
                            }
                        }
                    });
                });
        });
    });

    describe('getPriceFromVariant', () => {
        it('returns discounted price if variant is discounted', async () => {
            expect(snapAdminExtensionService.getPriceFromVariant({
                netPrice: 100,
                discountCampaign: {
                    discountedPrice: 80,
                },
            })).toBe(80);
        });

        it('returns net price if variant is not discounted', async () => {
            SnapAdmin.Service('shopwareDiscountCampaignService').isDiscountCampaignActive
                .mockImplementationOnce(() => false);

            expect(snapAdminExtensionService.getPriceFromVariant({
                netPrice: 100,
                discountCampaign: {
                    discountedPrice: 80,
                },
            })).toBe(100);
        });
    });

    describe('mapVariantToRecommendation', () => {
        it.each([
            ['free', 0],
            ['rent', 1],
            ['buy', 2],
            ['test', 3],
        ])('maps variant %s to position %d', (type, expectedRecommendation) => {
            expect(snapAdminExtensionService.mapVariantToRecommendation({ type })).toBe(expectedRecommendation);
        });
    });

    describe('getOpenLink', () => {
        it('returns always a open link for theme', async () => {
            const themeId = SnapAdmin.Utils.createId();

            const responses = global.repositoryFactoryMock.responses;
            responses.addResponse({
                method: 'Post',
                url: '/search-ids/theme',
                status: 200,
                response: {
                    data: [themeId],
                },
            });

            const openLink = await snapAdminExtensionService.getOpenLink({
                isTheme: true,
                type: snapAdminExtensionService.EXTENSION_TYPES.APP,
                name: 'SwagExampleApp',
            });

            expect(openLink).toEqual({
                name: 'sw.theme.manager.detail',
                params: { id: themeId },
            });
        });

        it('returns valid open link for app with main module', async () => {
            SnapAdmin.State.commit(
                'snapAdminApps/setApps',
                appModulesFixtures,
            );

            expect(await snapAdminExtensionService.getOpenLink({
                isTheme: false,
                type: snapAdminExtensionService.EXTENSION_TYPES.APP,
                name: 'testAppA',
            })).toEqual({
                name: 'sw.extension.module',
                params: {
                    appName: 'testAppA',
                },
            });
        });

        it('returns no open link for app without main module', async () => {
            SnapAdmin.State.commit(
                'snapAdminApps/setApps',
                appModulesFixtures,
            );

            expect(await snapAdminExtensionService.getOpenLink({
                isTheme: false,
                type: snapAdminExtensionService.EXTENSION_TYPES.APP,
                name: 'testAppB',
            })).toBeNull();
        });

        it('returns no open link if app can not be found', async () => {
            SnapAdmin.State.commit(
                'snapAdminApps/setApps',
                appModulesFixtures,
            );

            expect(await snapAdminExtensionService.getOpenLink({
                isTheme: false,
                type: snapAdminExtensionService.EXTENSION_TYPES.APP,
                name: 'ThisAppDoesNotExist',
            })).toBeNull();
        });

        it('returns no open link for plugins not registered', async () => {
            expect(await snapAdminExtensionService.getOpenLink({
                isTheme: false,
                type: snapAdminExtensionService.EXTENSION_TYPES.PLUGIN,
                name: 'SwagNoModule',
            })).toBeNull();
        });

        it('returns route for plugins registered', async () => {
            expect(await snapAdminExtensionService.getOpenLink({
                isTheme: false,
                type: snapAdminExtensionService.EXTENSION_TYPES.PLUGIN,
                name: 'ExamplePlugin',
                active: true,
            })).toEqual({
                label: null,
                name: 'test.foo',
            });
        });
    });
});
