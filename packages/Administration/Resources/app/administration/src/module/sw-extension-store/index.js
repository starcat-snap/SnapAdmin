import ExtensionStoreDataService from './service/extension-store-data.service';
import ExtensionLicenseService from './service/extension-store-licenses.service';

/* eslint-disable max-len, sw-deprecation-rules/private-feature-declarations */
SnapAdmin.Component.register('sw-extension-store-index', () => import('./page/sw-extension-store-index'));
SnapAdmin.Component.register('sw-extension-store-listing', () => import('./page/sw-extension-store-listing'));
SnapAdmin.Component.register('sw-extension-store-detail', () => import('./page/sw-extension-store-detail'));
SnapAdmin.Component.register('sw-extension-store-slider', () => import('./component/sw-extension-store-slider'));
SnapAdmin.Component.register('sw-extension-store-listing-filter', () => import('./component/sw-extension-store-listing-filter'));
SnapAdmin.Component.register('sw-extension-buy-modal', () => import('./component/sw-extension-buy-modal'));
SnapAdmin.Component.register('sw-extension-listing-card', () => import('./component/sw-extension-listing-card'));
SnapAdmin.Component.register('sw-extension-store-update-warning', () => import('./component/sw-extension-store-update-warning'));
SnapAdmin.Component.register('sw-extension-label', () => import('./component/sw-extension-store-label'));
SnapAdmin.Component.register('sw-extension-type-label', () => import('./component/sw-extension-store-type-label'));
SnapAdmin.Component.register('sw-extension-store-label-display', () => import('./component/sw-extension-store-label-display'));
SnapAdmin.Component.register('sw-extension-store-error-card', () => import('./component/sw-extension-store-error-card'));

SnapAdmin.Component.register('sw-extension-icon-polyfill', () => import('./component/sw-extension-icon-polyfill'));
/* eslint-disable-next-line sw-deprecation-rules/private-feature-declarations */

SnapAdmin.Application.addServiceProvider('extensionStoreDataService', () => {
    return new ExtensionStoreDataService(
        SnapAdmin.Application.getContainer('init').httpClient,
        SnapAdmin.Service('loginService'),
    );
});

SnapAdmin.Application.addServiceProvider('extensionStoreLicensesService', () => {
    return new ExtensionLicenseService(
        SnapAdmin.Application.getContainer('init').httpClient,
        SnapAdmin.Service('loginService'),
    );
});

SnapAdmin.Module.register('sw-extension-store', {
    title: 'sw-extension-store.general.title',
    name: 'sw-extension-store.general.title',
    routePrefixName: 'sw.extension',
    routePrefixPath: 'sw/extension',
    routes: {
        store: {
            path: 'store',
            redirect: {
                name: 'sw.extension.store.listing',
            },
            meta: {
                privilege: 'system.extension_store',
            },
            component: 'sw-extension-store-index',
            children: {
                listing: {
                    path: 'listing',
                    component: 'sw-extension-store-listing',
                    redirect: {
                        name: 'sw.extension.store.listing.app',
                    },
                    meta: {
                        privilege: 'system.extension_store',
                    },
                    children: {
                        app: {
                            path: 'app',
                            component: 'sw-extension-store-listing',
                            propsData: {
                                isTheme: false,
                            },
                            meta: {
                                privilege: 'system.extension_store',
                            },
                        },
                    },
                },
            },
        },
        'store.detail': {
            component: 'sw-extension-store-detail',
            path: 'store/detail/:id',
            meta: {
                parentPath: 'sw.extension.store',
                privilege: 'system.extension_store',
            },
            props: {
                default: (route) => {
                    return { id: route.params.id };
                },
            },
        },
    },
});
