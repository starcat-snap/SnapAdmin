/**
 * @package admin
 */

/* eslint-disable @typescript-eslint/no-explicit-any */
/* eslint-disable import/no-named-default */
import type { default as Bottle, Decorator } from 'bottlejs';
import type { Router } from 'vue-router';
// Import explicitly global types from admin-extension-sdk
import '@snap-admin/admin-extension-sdk';
import type FeatureService from 'src/app/service/feature.service';
import type { LoginService } from 'src/core/service/login.service';
import type { ContextState } from 'src/app/state/context.store';
import type { ExtensionComponentSectionsState } from 'src/app/state/extension-component-sections.store';
import type { AxiosInstance } from 'axios';
import type { SnapAdminClass } from 'src/core/snap-admin';
import type RepositoryFactory from 'src/core/data/repository-factory.data';
import type ExtensionSdkService from 'src/core/service/api/extension-sdk.service';
import type CustomSnippetApiService from 'src/core/service/api/custom-snippet.api.service';
import type LocaleFactory from 'src/core/factory/locale.factory';
import type UserActivityService from 'src/app/service/user-activity.service';
import type { FullState } from 'src/core/factory/state.factory';
import type ModuleFactory from 'src/core/factory/module.factory';
import type DirectiveFactory from 'src/core/factory/directive.factory';
import type EntityDefinitionFactory from 'src/core/factory/entity-definition.factory';
import type FilterFactoryData from 'src/core/data/filter-factory.data';
import type UserApiService from 'src/core/service/api/user.api.service';
import type ApiServiceFactory from 'src/core/factory/api-service.factory';
import type { App } from 'vue';
import type { I18n } from 'vue-i18n';
import type { Slots } from '@vue/runtime-core';
import type { Store } from 'vuex';
import type { ExtensionsState } from './app/state/extensions.store';
import type { ComponentConfig } from './core/factory/async-component.factory';
import type { TabsState } from './app/state/tabs.store';
import type { MenuItemState } from './app/state/menu-item.store';
import type { ModalsState } from './app/state/modals.store';
import type { ExtensionSdkModuleState } from './app/state/extension-sdk-module.store';
import type { MainModuleState } from './app/state/main-module.store';
import type { ActionButtonState } from './app/state/action-button.store';
import type StoreApiService from './core/service/api/store.api.service';
import type { SnapAdminExtensionsState } from './module/sw-extension/store/extensions.store';
import type AclService from './app/service/acl.service';
import type EntityValidationService from './app/service/entity-validation.service';
import type CustomEntityDefinitionService from './app/service/custom-entity-definition.service';
import type { SdkLocationState } from './app/state/sdk-location.store';
import type ExtensionHelperService from './app/service/extension-helper.service';
import type AsyncComponentFactory from './core/factory/async-component.factory';
import type FilterFactory from './core/factory/filter.factory';
import type StateStyleService from './app/service/state-style.service';
import type SystemConfigApiService from './core/service/api/system-config.api.service';
import type ConfigApiService from './core/service/api/config.api.service';
import type WorkerNotificationFactory from './core/factory/worker-notification.factory';
import type NotificationMixin from './app/mixin/notification.mixin';
import type ValidationMixin from './app/mixin/validation.mixin';
import type UserSettingsMixin from './app/mixin/user-settings.mixin';
import type SwInlineSnippetMixin from './app/mixin/sw-inline-snippet.mixin';
import type RemoveApiErrorMixin from './app/mixin/remove-api-error.mixin';
import type PositionMixin from './app/mixin/position.mixin';
import type PlaceholderMixin from './app/mixin/placeholder.mixin';
import type ListingMixin from './app/mixin/listing.mixin';
import type SwExtensionErrorMixin from './module/sw-extension/mixin/sw-extension-error.mixin';
import type SwFormFieldMixin from './app/mixin/form-field.mixin';
import type DiscardDetailPageChangesMixin from './app/mixin/discard-detail-page-changes.mixin';
import type PrivilegesService from './app/service/privileges.service';
import type { FileValidationService } from './app/service/file-validation.service';
import type { AdminHelpCenterState } from './app/state/admin-help-center.store';

// trick to make it an "external module" to support global type extension

// base methods for subContainer
// Export for modules and plugins to extend the service definitions
// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export interface SubContainer<ContainerName extends string> {
    $decorator(name: string | Decorator, func?: Decorator): this;
    $register(Obj: Bottle.IRegisterableObject): this;
    $list(): (keyof Bottle.IContainer[ContainerName])[];
}

// declare global types
declare global {
    /**
     * "any" type which looks more awful in the code so that spot easier
     * the places where we need to fix the TS types
     */
    type $TSFixMe = any;
    type $TSFixMeFunction = (...args: any[]) => any;

    /**
     * Dangerous "unknown" types which are specific enough but do not provide type safety.
     * You should avoid using these.
     */
    type $TSDangerUnknownObject = {[key: string|symbol]: unknown};

    /**
     * Make the SnapAdmin object globally available
     */
    const SnapAdmin: SnapAdminClass;
    interface Window {
        SnapAdmin: SnapAdminClass;
        _features_: {
            [featureName: string]: boolean
        };
        processingInactivityLogout?: boolean;
    }

    const _features_: {
        [featureName: string]: boolean
    };

    /**
     * Define global container for the bottle.js containers
     */
    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface ServiceContainer extends SubContainer<'service'>{
        loginService: LoginService,
        feature: FeatureService,
        menuService: $TSFixMe,
        privileges: PrivilegesService,
        customEntityDefinitionService: CustomEntityDefinitionService,
        acl: AclService,
        jsonApiParserService: $TSFixMe,
        validationService: $TSFixMe,
        entityValidationService: EntityValidationService,
        timezoneService: $TSFixMe,
        productStreamConditionService: $TSFixMe,
        customFieldDataProviderService: $TSFixMe,
        extensionHelperService: ExtensionHelperService,
        languageAutoFetchingService: $TSFixMe,
        stateStyleDataProviderService: StateStyleService,
        searchTypeService: $TSFixMe,
        localeToLanguageService: $TSFixMe,
        entityMappingService: $TSFixMe,
        shortcutService: $TSFixMe,
        licenseViolationService: $TSFixMe,
        localeHelper: $TSFixMe,
        filterService: $TSFixMe,
        mediaDefaultFolderService: $TSFixMe,
        appAclService: $TSFixMe,
        entityHydrator: $TSFixMe,
        entityFactory: $TSFixMe,
        userService: UserApiService,
        searchRankingService: $TSFixMe,
        searchPreferencesService: $TSFixMe,
        storeService: StoreApiService,
        repositoryFactory: RepositoryFactory,
        snippetService: $TSFixMe,
        recentlySearchService: $TSFixMe,
        extensionSdkService: ExtensionSdkService,
        customSnippetApiService: CustomSnippetApiService,
        userActivityService: UserActivityService,
        filterFactory: FilterFactoryData,
        systemConfigApiService: SystemConfigApiService,
        configService: ConfigApiService,
        fileValidationService: FileValidationService,
    }

    interface MixinContainer {
        notification: typeof NotificationMixin,
        validation: typeof ValidationMixin,
        'user-settings': typeof UserSettingsMixin,
        'sw-inline-snippet': typeof SwInlineSnippetMixin,
        'remove-api-error': typeof RemoveApiErrorMixin,
        position: typeof PositionMixin,
        placeholder: typeof PlaceholderMixin,
        listing: typeof ListingMixin,
        'sw-extension-error': typeof SwExtensionErrorMixin,
        'sw-form-field': typeof SwFormFieldMixin,
        'discard-detail-page-changes': typeof DiscardDetailPageChangesMixin,
    }

    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface InitContainer extends SubContainer<'init'>{
        state: $TSFixMe,
        router: $TSFixMe,
        httpClient: AxiosInstance,
    }
    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface FactoryContainer extends SubContainer<'factory'>{
        component: typeof AsyncComponentFactory,
        template: $TSFixMe,
        module: typeof ModuleFactory,
        entity: $TSFixMe,
        state: () => FullState,
        serviceFactory: $TSFixMe,
        classesFactory: $TSFixMe,
        mixin: $TSFixMe,
        directive: typeof DirectiveFactory,
        filter: typeof FilterFactory,
        locale: typeof LocaleFactory,
        shortcut: $TSFixMe,
        plugin: $TSFixMe,
        apiService: typeof ApiServiceFactory,
        entityDefinition: typeof EntityDefinitionFactory,
        workerNotification: typeof WorkerNotificationFactory,
    }

    interface FilterTypes {
        asset: (value: string) => string,
        currency: $TSFixMeFunction,
        date: (value: string, options?: Intl.DateTimeFormatOptions) => string,
        'file-size': $TSFixMeFunction,
        'media-name': $TSFixMeFunction,
        'stock-color-variant': $TSFixMeFunction
        striphtml: (value: string) => string,
        'thumbnail-size': $TSFixMeFunction,
        truncate: $TSFixMeFunction,
        'unicode-uri': $TSFixMeFunction,
        [key: string]: ((...args: any[]) => any)|undefined,
    }

    /**
     * Define global state for the Vuex store
     */
    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface VuexRootState {
        context: ContextState,
        extensions: ExtensionsState,
        tabs: TabsState,
        extensionComponentSections: ExtensionComponentSectionsState,
        session: {
            currentUser: EntitySchema.Entities['user'],
            userPending: boolean,
            languageId: string,
            currentLocale: string|null,
        },
        swCategoryDetail: $TSFixMe,
        menuItem: MenuItemState,
        extensionSdkModules: ExtensionSdkModuleState,
        extensionMainModules: MainModuleState,
        modals: ModalsState,
        actionButtons: ActionButtonState,
        snapAdminExtensions: SnapAdminExtensionsState,
        extensionEntryRoutes: $TSFixMe,
        sdkLocation: SdkLocationState,
        adminHelpCenter: AdminHelpCenterState,
    }

    /**
     * define global Component
     */
    type VueComponent = ComponentConfig;

    type apiContext = ContextState['api'];

    type appContext = ContextState['app'];

    /**
     * @see SnapAdmin\Core\Framework\Api\EventListener\ErrorResponseFactory
     */
    interface SnapAdminHttpError {
        code: string,
        status: string,
        title: string,
        detail: string,
        meta?: {
            file: string,
            line: string,
            trace?: { [key: string]: string },
            parameters?: object,
            previous?: SnapAdminHttpError,
        },
        trace?: { [key: string]: string },
    }

    interface StoreApiException extends SnapAdminHttpError {
        meta?: SnapAdminHttpError['meta'] & {
            documentationLink?: string,
        }
    }

    const flushPromises: () => Promise<void>;

    /**
     * @private This is a private method and should not be used outside of the test suite
     */
    const wrapTestComponent: (componentName: string, config?: { sync?: boolean }) => Promise<VueComponent>;
}

/**
 * Link global bottle.js container to the bottle.js container interface
 */
declare module 'bottlejs' { // Use the same module name as the import string
    type IContainerChildren = 'factory' | 'service' | 'init';

    interface IContainer {
        factory: FactoryContainer,
        service: ServiceContainer,
        init: InitContainer,
    }
}

/**
 * @deprecated tag:v6.7.0 - will be removed when Vue compat gets removed
 */
interface LegacyPublicProperties {
    /* eslint-disable @typescript-eslint/ban-types */
    $set(target: object, key: string, value: any): void;
    $delete(target: object, key: string): void;
    $mount(el?: string | Element): this;
    $destroy(): void;
    $scopedSlots: Slots;
    $on(event: string | string[], fn: Function): this;
    $once(event: string, fn: Function): this;
    $off(event?: string | string[], fn?: Function): this;
    $children: LegacyPublicProperties[];
    $listeners: Record<string, Function | Function[]>;
    /* eslint-enable @typescript-eslint/ban-types */
}

interface CustomProperties extends ServiceContainer, LegacyPublicProperties {
    $createTitle: (identifier?: string | null) => string,
    $router: Router,
    $store: Store<VuexRootState>,
    // $route: SwRouteLocationNormalizedLoaded,
    // eslint-disable-next-line @typescript-eslint/ban-types
    $tc: I18n<{}, {}, {}, string, true>['global']['tc'],
    // eslint-disable-next-line @typescript-eslint/ban-types
    $t: I18n<{}, {}, {}, string, true>['global']['t'],
}

declare module 'vue' {
    // eslint-disable-next-line @typescript-eslint/no-empty-interface
    interface ComponentCustomProperties extends CustomProperties {}

    interface ComponentCustomOptions {
        shortcuts?: {
            [key: string]: string | {
                active: boolean | ((this: App) => boolean),
                method: string
            }
        },

        extensionApiDevtoolInformation?: {
            property?: string,
            method?: string,
            positionId?: (currentComponent: any) => string,
            helpText?: string,
        }
    }

    interface PropOptions {
        validValues?: any[]
    }
}

declare module '@vue/runtime-core' {
    // eslint-disable-next-line @typescript-eslint/no-shadow,@typescript-eslint/no-empty-interface
    interface App extends CustomProperties {}
}

declare module 'axios' {
    interface AxiosRequestConfig {
        version?: number
    }
}
