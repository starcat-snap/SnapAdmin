import initializeApiServices from 'src/app/init-pre/api-services.init';

describe('src/app/init-pre/api-services.init.ts', () => {
    /**
     * [
     *         'aclApiService',
     *         'appActionButtonService',
     *         'appCmsBlocks',
     *         'appModulesService',
     *         'appUrlChangeService',
     *         'businessEventService',
     *         'cacheApiService',
     *         'calculate-price',
     *         'cartStoreService',
     *         'checkoutStoreService',
     *         'configService',
     *         'customSnippetApiService',
     *         'customerGroupRegistrationService',
     *         'customerValidationService',
     *         'documentService',
     *         'excludedSearchTermService',
     *         'extensionSdkService',
     *         'firstRunWizardService',
     *         'flowActionService',
     *         'importExportService',
     *         'integrationService',
     *         'knownIpsService',
     *         'languagePluginService',
     *         'mailService',
     *         'mediaFolderService',
     *         'mediaService',
     *         'messageQueueService',
     *         'notificationsService',
     *         'numberRangeService',
     *         'orderDocumentApiService',
     *         'orderStateMachineService',
     *         'orderService',
     *         'productExportService',
     *         'productStreamPreviewService',
     *         'promotionSyncService',
     *         'recommendationsService',
     *         'ruleConditionsConfigApiService',
     *         'salesChannelService',
     *         'scheduledTaskService',
     *         'searchService',
     *         'seoUrlTemplateService',
     *         'seoUrlService',
     *         'snippetSetService',
     *         'snippetService',
     *         'stateMachineService',
     *         'contextStoreService',
     *         'storeService',
     *         'syncService',
     *         'systemConfigApiService',
     *         'tagApiService',
     *         'updateService',
     *         'userActivityApiService',
     *         'userConfigService',
     *         'userInputSanitizeService',
     *         'userRecoveryService',
     *         'userValidationService',
     *         'userService'
     *       ]
     */

    it('should initialize the api services', () => {
        expect(SnapAdmin.Service('aclApiService')).toBeUndefined();
        expect(SnapAdmin.Service('appActionButtonService')).toBeUndefined();
        expect(SnapAdmin.Service('appCmsBlocks')).toBeUndefined();
        expect(SnapAdmin.Service('appModulesService')).toBeUndefined();
        expect(SnapAdmin.Service('appUrlChangeService')).toBeUndefined();
        expect(SnapAdmin.Service('businessEventService')).toBeUndefined();
        expect(SnapAdmin.Service('cacheApiService')).toBeUndefined();
        expect(SnapAdmin.Service('calculate-price')).toBeUndefined();
        expect(SnapAdmin.Service('cartStoreService')).toBeUndefined();
        expect(SnapAdmin.Service('checkoutStoreService')).toBeUndefined();
        expect(SnapAdmin.Service('configService')).toBeUndefined();
        expect(SnapAdmin.Service('customSnippetApiService')).toBeUndefined();
        expect(SnapAdmin.Service('customerGroupRegistrationService')).toBeUndefined();
        expect(SnapAdmin.Service('customerValidationService')).toBeUndefined();
        expect(SnapAdmin.Service('documentService')).toBeUndefined();
        expect(SnapAdmin.Service('excludedSearchTermService')).toBeUndefined();
        expect(SnapAdmin.Service('extensionSdkService')).toBeUndefined();
        expect(SnapAdmin.Service('firstRunWizardService')).toBeUndefined();
        expect(SnapAdmin.Service('flowActionService')).toBeUndefined();
        expect(SnapAdmin.Service('importExportService')).toBeUndefined();
        expect(SnapAdmin.Service('integrationService')).toBeUndefined();
        expect(SnapAdmin.Service('knownIpsService')).toBeUndefined();
        expect(SnapAdmin.Service('languagePluginService')).toBeUndefined();
        expect(SnapAdmin.Service('mailService')).toBeUndefined();
        expect(SnapAdmin.Service('mediaFolderService')).toBeUndefined();
        expect(SnapAdmin.Service('mediaService')).toBeUndefined();
        expect(SnapAdmin.Service('messageQueueService')).toBeUndefined();
        expect(SnapAdmin.Service('notificationsService')).toBeUndefined();
        expect(SnapAdmin.Service('numberRangeService')).toBeUndefined();
        expect(SnapAdmin.Service('orderDocumentApiService')).toBeUndefined();
        expect(SnapAdmin.Service('orderStateMachineService')).toBeUndefined();
        expect(SnapAdmin.Service('orderService')).toBeUndefined();
        expect(SnapAdmin.Service('productExportService')).toBeUndefined();
        expect(SnapAdmin.Service('productStreamPreviewService')).toBeUndefined();
        expect(SnapAdmin.Service('promotionSyncService')).toBeUndefined();
        expect(SnapAdmin.Service('recommendationsService')).toBeUndefined();
        expect(SnapAdmin.Service('ruleConditionsConfigApiService')).toBeUndefined();
        expect(SnapAdmin.Service('salesChannelService')).toBeUndefined();
        expect(SnapAdmin.Service('scheduledTaskService')).toBeUndefined();
        expect(SnapAdmin.Service('searchService')).toBeUndefined();
        expect(SnapAdmin.Service('seoUrlTemplateService')).toBeUndefined();
        expect(SnapAdmin.Service('seoUrlService')).toBeUndefined();
        expect(SnapAdmin.Service('snippetSetService')).toBeUndefined();
        expect(SnapAdmin.Service('snippetService')).toBeUndefined();
        expect(SnapAdmin.Service('stateMachineService')).toBeUndefined();
        expect(SnapAdmin.Service('contextStoreService')).toBeUndefined();
        expect(SnapAdmin.Service('storeService')).toBeUndefined();
        expect(SnapAdmin.Service('syncService')).toBeUndefined();
        expect(SnapAdmin.Service('systemConfigApiService')).toBeUndefined();
        expect(SnapAdmin.Service('tagApiService')).toBeUndefined();
        expect(SnapAdmin.Service('updateService')).toBeUndefined();
        expect(SnapAdmin.Service('userActivityApiService')).toBeUndefined();
        expect(SnapAdmin.Service('userConfigService')).toBeUndefined();
        expect(SnapAdmin.Service('userInputSanitizeService')).toBeUndefined();
        expect(SnapAdmin.Service('userRecoveryService')).toBeUndefined();
        expect(SnapAdmin.Service('userValidationService')).toBeUndefined();
        expect(SnapAdmin.Service('userService')).toBeUndefined();

        initializeApiServices();

        expect(SnapAdmin.Service('aclApiService')).toBeDefined();
        expect(SnapAdmin.Service('appActionButtonService')).toBeDefined();
        expect(SnapAdmin.Service('appCmsBlocks')).toBeDefined();
        expect(SnapAdmin.Service('appModulesService')).toBeDefined();
        expect(SnapAdmin.Service('appUrlChangeService')).toBeDefined();
        expect(SnapAdmin.Service('businessEventService')).toBeDefined();
        expect(SnapAdmin.Service('cacheApiService')).toBeDefined();
        expect(SnapAdmin.Service('calculate-price')).toBeDefined();
        expect(SnapAdmin.Service('cartStoreService')).toBeDefined();
        expect(SnapAdmin.Service('checkoutStoreService')).toBeDefined();
        expect(SnapAdmin.Service('configService')).toBeDefined();
        expect(SnapAdmin.Service('customSnippetApiService')).toBeDefined();
        expect(SnapAdmin.Service('customerGroupRegistrationService')).toBeDefined();
        expect(SnapAdmin.Service('customerValidationService')).toBeDefined();
        expect(SnapAdmin.Service('documentService')).toBeDefined();
        expect(SnapAdmin.Service('excludedSearchTermService')).toBeDefined();
        expect(SnapAdmin.Service('extensionSdkService')).toBeDefined();
        expect(SnapAdmin.Service('firstRunWizardService')).toBeDefined();
        expect(SnapAdmin.Service('flowActionService')).toBeDefined();
        expect(SnapAdmin.Service('importExportService')).toBeDefined();
        expect(SnapAdmin.Service('integrationService')).toBeDefined();
        expect(SnapAdmin.Service('knownIpsService')).toBeDefined();
        expect(SnapAdmin.Service('languagePluginService')).toBeDefined();
        expect(SnapAdmin.Service('mailService')).toBeDefined();
        expect(SnapAdmin.Service('mediaFolderService')).toBeDefined();
        expect(SnapAdmin.Service('mediaService')).toBeDefined();
        expect(SnapAdmin.Service('messageQueueService')).toBeDefined();
        expect(SnapAdmin.Service('notificationsService')).toBeDefined();
        expect(SnapAdmin.Service('numberRangeService')).toBeDefined();
        expect(SnapAdmin.Service('orderDocumentApiService')).toBeDefined();
        expect(SnapAdmin.Service('orderStateMachineService')).toBeDefined();
        expect(SnapAdmin.Service('orderService')).toBeDefined();
        expect(SnapAdmin.Service('productExportService')).toBeDefined();
        expect(SnapAdmin.Service('productStreamPreviewService')).toBeDefined();
        expect(SnapAdmin.Service('promotionSyncService')).toBeDefined();
        expect(SnapAdmin.Service('recommendationsService')).toBeDefined();
        expect(SnapAdmin.Service('ruleConditionsConfigApiService')).toBeDefined();
        expect(SnapAdmin.Service('salesChannelService')).toBeDefined();
        expect(SnapAdmin.Service('scheduledTaskService')).toBeDefined();
        expect(SnapAdmin.Service('searchService')).toBeDefined();
        expect(SnapAdmin.Service('seoUrlTemplateService')).toBeDefined();
        expect(SnapAdmin.Service('seoUrlService')).toBeDefined();
        expect(SnapAdmin.Service('snippetSetService')).toBeDefined();
        expect(SnapAdmin.Service('snippetService')).toBeDefined();
        expect(SnapAdmin.Service('stateMachineService')).toBeDefined();
        expect(SnapAdmin.Service('contextStoreService')).toBeDefined();
        expect(SnapAdmin.Service('storeService')).toBeDefined();
        expect(SnapAdmin.Service('syncService')).toBeDefined();
        expect(SnapAdmin.Service('systemConfigApiService')).toBeDefined();
        expect(SnapAdmin.Service('tagApiService')).toBeDefined();
        expect(SnapAdmin.Service('updateService')).toBeDefined();
        expect(SnapAdmin.Service('userActivityApiService')).toBeDefined();
        expect(SnapAdmin.Service('userConfigService')).toBeDefined();
        expect(SnapAdmin.Service('userInputSanitizeService')).toBeDefined();
        expect(SnapAdmin.Service('userRecoveryService')).toBeDefined();
        expect(SnapAdmin.Service('userValidationService')).toBeDefined();
        expect(SnapAdmin.Service('userService')).toBeDefined();
    });
});
