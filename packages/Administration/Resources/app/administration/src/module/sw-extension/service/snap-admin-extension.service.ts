import type { RouteLocationNamedRaw, RouteLocation } from 'vue-router';
import type StoreApiService from 'src/core/service/api/store.api.service';
import type {
    ExtensionStoreActionService,
    Extension,
    ExtensionVariant,
    ExtensionVariantType,
    ExtensionType,
} from './extension-store-action.service';

type EXTENSION_VARIANT_TYPES = {
    [Property in Uppercase<ExtensionVariantType>]: Lowercase<Property>
}

type EXTENSION_TYPES = {
    [Property in Uppercase<ExtensionType>]: Lowercase<Property>
}

interface LabeledLocation extends RouteLocation {
    label: string|null
}

/**
 * @package services-settings
 * @private
 */
export default class SnapAdminExtensionService {
    public readonly EXTENSION_VARIANT_TYPES: Readonly<EXTENSION_VARIANT_TYPES>;

    private readonly EXTENSION_TYPES: Readonly<EXTENSION_TYPES>;

    constructor(
        private readonly extensionStoreActionService: ExtensionStoreActionService,
        private readonly storeApiService: StoreApiService,
    ) {
        this.EXTENSION_VARIANT_TYPES = Object.freeze({
            RENT: 'rent',
            BUY: 'buy',
            FREE: 'free',
        });

        this.EXTENSION_TYPES = Object.freeze({
            APP: 'app',
            PLUGIN: 'plugin',
        });
    }

    public async installAndActivateExtension(extensionName: string, type: ExtensionType): Promise<void> {
        await this.extensionStoreActionService.installExtension(extensionName, type);
        await this.extensionStoreActionService.activateExtension(extensionName, type);
        await this.updateExtensionData();
    }

    public async installExtension(extensionName: string, type: ExtensionType): Promise<void> {
        await this.extensionStoreActionService.installExtension(extensionName, type);

        await this.updateExtensionData();
    }

    public async updateExtension(extensionName: string, type: ExtensionType, allowNewPrivileges = false): Promise<void> {
        await this.extensionStoreActionService.updateExtension(extensionName, type, allowNewPrivileges);

        await this.updateExtensionData();
    }

    public async uninstallExtension(extensionName: string, type: ExtensionType, removeData: boolean): Promise<void> {
        await this.extensionStoreActionService.uninstallExtension(extensionName, type, removeData);

        await this.updateExtensionData();
    }

    public async removeExtension(extensionName: string, type: ExtensionType): Promise<void> {
        await this.extensionStoreActionService.removeExtension(extensionName, type);

        await this.updateExtensionData();
    }

    public async cancelLicense(licenseId: number): Promise<void> {
        await this.extensionStoreActionService.cancelLicense(licenseId);
    }

    public async activateExtension(extensionId: string, type: ExtensionType): Promise<void> {
        await this.extensionStoreActionService.activateExtension(extensionId, type);

        this.updateModules();
    }

    public async deactivateExtension(extensionId: string, type: ExtensionType): Promise<void> {
        await this.extensionStoreActionService.deactivateExtension(extensionId, type);

        this.updateModules();
    }

    public async updateExtensionData(): Promise<void> {
        SnapAdmin.State.commit('snapAdminExtensions/loadMyExtensions');

        try {
            await this.extensionStoreActionService.refresh();

            // eslint-disable-next-line @typescript-eslint/no-unsafe-assignment
            const myExtensions = await this.extensionStoreActionService.getMyExtensions();

            SnapAdmin.State.commit('snapAdminExtensions/myExtensions', myExtensions);

            this.updateModules();
        } finally {
            SnapAdmin.State.commit('snapAdminExtensions/setLoading', false);
        }
    }

    public async checkLogin(): Promise<void> {
        try {
            const { userInfo } = await this.storeApiService.checkLogin();
            SnapAdmin.State.commit('snapAdminExtensions/setUserInfo', userInfo);
        } catch {
            SnapAdmin.State.commit('snapAdminExtensions/setUserInfo', null);
        }
    }

    public orderVariantsByRecommendation(variants: ExtensionVariant[]): ExtensionVariant[] {
        const discounted = variants.filter(() => this.isVariantDiscounted());
        const notDiscounted = variants.filter(() => !this.isVariantDiscounted());

        return [
            ...this.orderByType(discounted),
            ...this.orderByType(notDiscounted),
        ];
    }

    public isVariantDiscounted(): boolean {
        return false;
    }

    public getPriceFromVariant(variant: ExtensionVariant) {
        if (this.isVariantDiscounted()) {
            // null assertion is fine here because we do all checks in isVariantDiscounted
            // eslint-disable-next-line @typescript-eslint/no-non-null-assertion
            return variant.discountCampaign!.discountedPrice!;
        }

        return variant.netPrice;
    }

    public mapVariantToRecommendation(variant: ExtensionVariant) {
        switch (variant.type) {
            case this.EXTENSION_VARIANT_TYPES.FREE:
                return 0;
            case this.EXTENSION_VARIANT_TYPES.RENT:
                return 1;
            case this.EXTENSION_VARIANT_TYPES.BUY:
                return 2;
            default:
                return 3;
        }
    }

    public getOpenLink(extension: Extension): LabeledLocation | null | RouteLocationNamedRaw {
        // Only show open link when extension is active. The route is maybe not available
        if (!extension.active) {
            return null;
        }

        /* eslint-disable @typescript-eslint/no-unsafe-member-access,@typescript-eslint/no-unsafe-assignment */
        const entryRoutes = SnapAdmin.State.get('extensionEntryRoutes').routes;

        if (entryRoutes[extension.name] !== undefined) {
            return {
                name: entryRoutes[extension.name].route,
                label: entryRoutes[extension.name].label ?? null,
            } as LabeledLocation;
        }
        /* eslint-enable */

        return null;
    }

    private updateModules() {
        // SnapAdmin.State.commit('snapAdminApps/setApps', []);
    }

    private orderByType(variants: ExtensionVariant[]) {
        const valueTypes = variants.map((variant, index) => {
            return { value: this.mapVariantToRecommendation(variant), index };
        });

        valueTypes.sort((first, second) => {
            return first.value - second.value;
        });

        return valueTypes.map((type) => {
            return variants[type.index];
        });
    }
}
