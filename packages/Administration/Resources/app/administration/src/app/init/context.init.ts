/**
 * @package admin
 */

/* Is covered by E2E tests */
import { publish } from '@snap-admin/admin-extension-sdk/es/channel';

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default function initializeContext(): void {
    // Handle incoming context requests from the ExtensionAPI
    SnapAdmin.ExtensionAPI.handle('contextCurrency', () => {
        return {
            systemCurrencyId: SnapAdmin.Context.app.systemCurrencyId ?? '',
            systemCurrencyISOCode: SnapAdmin.Context.app.systemCurrencyISOCode ?? '',
        };
    });

    SnapAdmin.ExtensionAPI.handle('contextEnvironment', () => {
        return SnapAdmin.Context.app.environment ?? 'production';
    });

    SnapAdmin.ExtensionAPI.handle('contextLanguage', () => {
        return {
            languageId: SnapAdmin.Context.api.languageId ?? '',
            systemLanguageId: SnapAdmin.Context.api.systemLanguageId ?? '',
        };
    });

    SnapAdmin.ExtensionAPI.handle('contextLocale', () => {
        return {
            fallbackLocale: SnapAdmin.Context.app.fallbackLocale ?? '',
            locale: SnapAdmin.State.get('session').currentLocale ?? '',
        };
    });

    SnapAdmin.ExtensionAPI.handle('contextSnapAdminVersion', () => {
        return SnapAdmin.Context.app.config.version ?? '';
    });

    SnapAdmin.ExtensionAPI.handle('contextModuleInformation', (_, additionalInformation) => {
        const extension = Object.values(SnapAdmin.State.get('extensions'))
            .find(ext => ext.baseUrl.startsWith(additionalInformation._event_.origin));

        if (!extension) {
            return {
                modules: [],
            };
        }

        // eslint-disable-next-line max-len,@typescript-eslint/no-unsafe-call,@typescript-eslint/no-unsafe-member-access
        const modules = SnapAdmin.State.getters['extensionSdkModules/getRegisteredModuleInformation'](extension.baseUrl) as Array< {
            displaySearchBar: boolean,
            heading: string,
            id: string,
            locationId: string
        }>;

        return {
            modules,
        };
    });

    SnapAdmin.ExtensionAPI.handle('contextUserInformation', (_, { _event_ }) => {
        const appOrigin = _event_.origin;
        const extension = Object.entries(SnapAdmin.State.get('extensions')).find((ext) => {
            return ext[1].baseUrl.startsWith(appOrigin);
        });

        if (!extension) {
            return Promise.reject(new Error(`Could not find a extension with the given event origin "${_event_.origin}"`));
        }

        if (!extension[1]?.permissions?.read?.includes('user')) {
            return Promise.reject(new Error(`Extension "${extension[0]}" does not have the permission to read users`));
        }

        const currentUser = SnapAdmin.State.get('session').currentUser;

        return Promise.resolve({
            aclRoles: currentUser.aclRoles as unknown as Array<{
                name: string,
                type: string,
                id: string,
                privileges: Array<string>,
            }>,
            active: !!currentUser.active,
            admin: !!currentUser.admin,
            avatarId: currentUser.avatarId ?? '',
            email: currentUser.email ?? '',
            name: currentUser.name ?? '',
            id: currentUser.id ?? '',
            phone: currentUser.phone ?? '',
            localeId: currentUser.localeId ?? '',
            title: currentUser.title ?? '',
            // @ts-expect-error - type is not defined in entity directly
            type: (currentUser.type as unknown as string) ?? '',
            username: currentUser.username ?? '',
        });
    });

    SnapAdmin.ExtensionAPI.handle('contextAppInformation', (_, { _event_ }) => {
        const appOrigin = _event_.origin;
        const extension = Object.entries(SnapAdmin.State.get('extensions')).find((ext) => {
            return ext[1].baseUrl.startsWith(appOrigin);
        });

        if (!extension || !extension[0] || !extension[1]) {
            const type: 'app'|'plugin' = 'app';

            return {
                name: 'unknown',
                type: type,
                version: '0.0.0',
            };
        }

        return {
            name: extension[0],
            type: extension[1].type,
            version: extension[1].version ?? '',
        };
    });

    SnapAdmin.State.watch((state) => {
        return {
            languageId: state.context.api.languageId,
            systemLanguageId: state.context.api.systemLanguageId,
        };
    }, ({ languageId, systemLanguageId }, { languageId: oldLanguageId, systemLanguageId: oldSystemLanguageId }) => {
        if (languageId === oldLanguageId && systemLanguageId === oldSystemLanguageId) {
            return;
        }

        void publish('contextLanguage', {
            languageId: languageId ?? '',
            systemLanguageId: systemLanguageId ?? '',
        });
    });

    SnapAdmin.State.watch((state) => {
        return {
            fallbackLocale: state.context.app.fallbackLocale,
            locale: state.session.currentLocale,
        };
    }, ({ fallbackLocale, locale }, { fallbackLocale: oldFallbackLocale, locale: oldLocale }) => {
        if (fallbackLocale === oldFallbackLocale && locale === oldLocale) {
            return;
        }

        void publish('contextLocale', {
            locale: locale ?? '',
            fallbackLocale: fallbackLocale ?? '',
        });
    });
}
