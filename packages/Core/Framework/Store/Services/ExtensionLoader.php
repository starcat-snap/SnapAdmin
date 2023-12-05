<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\App\Aggregate\AppTranslation\AppTranslationCollection;
use SnapAdmin\Core\Framework\App\AppCollection;
use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\App\Lifecycle\AbstractAppLoader;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Aggregation\Bucket\TermsAggregation;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\AggregationResult\Bucket\TermsResult;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginCollection;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;
use SnapAdmin\Core\Framework\Store\Authentication\LocaleProvider;
use SnapAdmin\Core\Framework\Store\Struct\BinaryCollection;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionCollection;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionStruct;
use SnapAdmin\Core\Framework\Store\Struct\FaqCollection;
use SnapAdmin\Core\Framework\Store\Struct\ImageCollection;
use SnapAdmin\Core\Framework\Store\Struct\PermissionCollection;
use SnapAdmin\Core\Framework\Store\Struct\StoreCategoryCollection;
use SnapAdmin\Core\Framework\Store\Struct\StoreCollection;
use SnapAdmin\Core\Framework\Store\Struct\VariantCollection;
use SnapAdmin\Core\System\Locale\LanguageLocaleCodeProvider;
use SnapAdmin\Core\System\SystemConfig\Service\ConfigurationService;
use SnapAdmin\Storefront\Framework\ThemeInterface;
use Symfony\Component\Intl\Languages;
use Symfony\Component\Intl\Locales;

/**
 * @internal
 */
#[Package('services-settings')]
class ExtensionLoader
{
    private const DEFAULT_LOCALE = 'en_GB';

    /**
     * @var array<string>|null
     */
    private ?array $installedThemeNames = null;

    public function __construct(
        private readonly ?EntityRepository $themeRepository,
        private readonly AbstractAppLoader $appLoader,
        private readonly ConfigurationService $configurationService,
        private readonly LocaleProvider $localeProvider,
        private readonly LanguageLocaleCodeProvider $languageLocaleProvider
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public function loadFromArray(Context $context, array $data, ?string $locale = null): ExtensionStruct
    {
        if ($locale === null) {
            $locale = $this->localeProvider->getLocaleFromContext($context);
        }

        $localeWithUnderscore = str_replace('-', '_', $locale);
        $data = $this->prepareArrayData($data, $localeWithUnderscore);

        return ExtensionStruct::fromArray($data);
    }

    /**
     * @param array<array<string, mixed>> $data
     */
    public function loadFromListingArray(Context $context, array $data): ExtensionCollection
    {
        $locale = $this->localeProvider->getLocaleFromContext($context);
        $localeWithUnderscore = str_replace('-', '_', $locale);
        $extensions = new ExtensionCollection();

        foreach ($data as $extension) {
            $extension = ExtensionStruct::fromArray($this->prepareArrayData($extension, $localeWithUnderscore));
            $extensions->set($extension->getName(), $extension);
        }

        return $extensions;
    }

    public function loadFromAppCollection(Context $context, AppCollection $collection): ExtensionCollection
    {
        $data = [];
        foreach ($collection as $app) {
            $data[] = $this->prepareAppData($context, $app);
        }

        $registeredApps = $this->loadFromListingArray($context, $data);

        // Enrich apps from filesystem
        $localApps = $this->loadLocalAppsCollection($context);

        foreach ($localApps as $name => $app) {
            if ($registeredApps->has($name)) {
                /** @var ExtensionStruct $registeredApp */
                $registeredApp = $registeredApps->get($name);

                $registeredApp->setIsTheme($app->isTheme());

                // Set version of local app to registered app if newer
                if (version_compare((string) $app->getVersion(), (string) $registeredApp->getVersion(), '>')) {
                    $registeredApp->setLatestVersion($app->getVersion());
                }

                continue;
            }

            $registeredApps->set($name, $app);
        }

        return $registeredApps;
    }

    public function loadFromPluginCollection(Context $context, PluginCollection $collection): ExtensionCollection
    {
        $extensions = new ExtensionCollection();

        foreach ($collection as $app) {
            $plugin = $this->loadFromPlugin($context, $app);
            $extensions->set($plugin->getName(), $plugin);
        }

        return $extensions;
    }

    public function getLocaleCodeFromLanguageId(Context $context, ?string $languageId = null): ?string
    {
        if ($languageId === null) {
            $languageId = $context->getLanguageId();
        }

        $id = $this->getLocalesCodesFromLanguageIds([$languageId]);

        if (empty($id)) {
            return null;
        }

        return $id[0];
    }

    /**
     * @param array<string> $languageIds
     *
     * @return array<string>
     */
    public function getLocalesCodesFromLanguageIds(array $languageIds): array
    {
        $codes = array_values($this->languageLocaleProvider->getLocalesForLanguageIds($languageIds));
        sort($codes);

        return array_map(static fn (string $locale): string => str_replace('-', '_', $locale), $codes);
    }

    private function loadFromPlugin(Context $context, PluginEntity $plugin): ExtensionStruct
    {
        $isTheme = false;

        if (interface_exists(ThemeInterface::class) && class_exists($plugin->getBaseClass())) {
            $implementedInterfaces = class_implements($plugin->getBaseClass());

            if (\is_array($implementedInterfaces)) {
                $isTheme = \array_key_exists(ThemeInterface::class, $implementedInterfaces);
            }
        }

        $data = [
            'localId' => $plugin->getId(),
            'description' => $plugin->getTranslation('description'),
            'name' => $plugin->getName(),
            'label' => $plugin->getTranslation('label'),
            'producerName' => $plugin->getAuthor(),
            'license' => $plugin->getLicense(),
            'version' => $plugin->getVersion(),
            'latestVersion' => $plugin->getUpgradeVersion(),
            'iconRaw' => $plugin->getIcon(),
            'installedAt' => $plugin->getInstalledAt(),
            'active' => $plugin->getActive(),
            'type' => ExtensionStruct::EXTENSION_TYPE_PLUGIN,
            'isTheme' => $isTheme,
            'configurable' => $this->configurationService->checkConfiguration(sprintf('%s.config', $plugin->getName()), $context),
            'updatedAt' => $plugin->getUpgradedAt(),
            'allowDisable' => true,
        ];

        return ExtensionStruct::fromArray($this->replaceCollections($data));
    }

    /**
     * @return array<string>
     */
    private function getInstalledThemeNames(Context $context): array
    {
        if ($this->installedThemeNames === null && $this->themeRepository instanceof EntityRepository) {
            $themeNameAggregationName = 'theme_names';
            $criteria = new Criteria();
            $criteria->addAggregation(new TermsAggregation($themeNameAggregationName, 'technicalName'));

            /** @var TermsResult $themeNameAggregation */
            $themeNameAggregation = $this->themeRepository->aggregate($criteria, $context)->get($themeNameAggregationName);

            return $this->installedThemeNames = $themeNameAggregation->getKeys();
        }

        return $this->installedThemeNames ?? [];
    }

    private function loadLocalAppsCollection(Context $context): ExtensionCollection
    {
        $apps = $this->appLoader->load();
        $collection = new ExtensionCollection();
        $language = $this->localeProvider->getLocaleFromContext($context);

        foreach ($apps as $name => $app) {
            if ($icon = $app->getMetadata()->getIcon()) {
                $icon = $this->appLoader->loadFile($app->getPath(), $icon);
            }

            $appArray = $app->getMetadata()->toArray($language);

            $row = [
                'description' => isset($appArray['description']) ? $this->getTranslationFromArray($appArray['description'], $language) : '',
                'name' => $name,
                'label' => isset($appArray['label']) ? $this->getTranslationFromArray($appArray['label'], $language) : '',
                'producerName' => $app->getMetadata()->getAuthor(),
                'license' => $app->getMetadata()->getLicense(),
                'version' => $app->getMetadata()->getVersion(),
                'latestVersion' => $app->getMetadata()->getVersion(),
                'iconRaw' => $icon ? base64_encode($icon) : null,
                'installedAt' => null,
                'active' => false,
                'type' => ExtensionStruct::EXTENSION_TYPE_APP,
                'isTheme' => is_file($app->getPath() . '/Resources/theme.json'),
                'privacyPolicyExtension' => isset($appArray['privacyPolicyExtensions']) ? $this->getTranslationFromArray($appArray['privacyPolicyExtensions'], $language, 'en-GB') : '',
                'privacyPolicyLink' => $app->getMetadata()->getPrivacy(),
            ];

            $collection->set($name, $this->loadFromArray($context, $row, $language));
        }

        return $collection;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, StoreCollection|mixed|null>
     */
    private function prepareArrayData(array $data, ?string $locale): array
    {
        return $this->translateExtensionLanguages($this->replaceCollections($data), $locale);
    }

    /**
     * @return array<string, mixed>
     */
    private function prepareAppData(Context $context, AppEntity $app): array
    {
        $installedThemeNames = $this->getInstalledThemeNames($context);

        $data = [
            'localId' => $app->getId(),
            'description' => $app->getTranslation('description'),
            'name' => $app->getName(),
            'label' => $app->getTranslation('label'),
            'producerName' => $app->getAuthor(),
            'license' => $app->getLicense(),
            'version' => $app->getVersion(),
            'privacyPolicyLink' => $app->getPrivacy(),
            'iconRaw' => $app->getIcon(),
            'installedAt' => $app->getCreatedAt(),
            'permissions' => $app->getAclRole() !== null ? $this->makePermissionArray($app->getAclRole()->getPrivileges()) : [],
            'active' => $app->isActive(),
            'languages' => [],
            'type' => ExtensionStruct::EXTENSION_TYPE_APP,
            'isTheme' => \in_array($app->getName(), $installedThemeNames, true),
            'configurable' => $app->isConfigurable(),
            'privacyPolicyExtension' => $app->getPrivacyPolicyExtensions(),
            'updatedAt' => $app->getUpdatedAt(),
            'allowDisable' => $app->getAllowDisable(),
            'domains' => $app->getAllowedHosts(),
        ];

        $appTranslations = $app->getTranslations();

        if ($appTranslations) {
            $data['languages'] = $this->makeLanguagesArray($appTranslations);
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, StoreCollection|mixed|null>
     */
    private function replaceCollections(array $data): array
    {
        $replacements = [
            'variants' => VariantCollection::class,
            'faq' => FaqCollection::class,
            'binaries' => BinaryCollection::class,
            'images' => ImageCollection::class,
            'categories' => StoreCategoryCollection::class,
            'permissions' => PermissionCollection::class,
        ];

        foreach ($replacements as $key => $collectionClass) {
            $data[$key] = new $collectionClass($data[$key] ?? []);
        }

        return $data;
    }

    /**
     * @param array<string> $appPrivileges
     *
     * @return array<array<string, string>>
     */
    private function makePermissionArray(array $appPrivileges): array
    {
        $permissions = [];

        foreach ($appPrivileges as $privilege) {
            if (substr_count($privilege, ':') === 1) {
                $entityAndOperation = explode(':', $privilege);
                if (\array_key_exists($entityAndOperation[1], AclRoleDefinition::PRIVILEGE_DEPENDENCE)) {
                    /** @var array<string, string> $permission */
                    $permission = array_combine(['entity', 'operation'], $entityAndOperation);
                    $permissions[] = $permission;

                    continue;
                }
            }

            $permissions[] = ['operation' => $privilege, 'entity' => 'additional_privileges'];
        }

        return $permissions;
    }

    /**
     * @param array<string, StoreCollection|mixed|null> $data
     *
     * @return array<string, StoreCollection|mixed|null>
     */
    private function translateExtensionLanguages(array $data, ?string $locale = self::DEFAULT_LOCALE): array
    {
        if (!isset($data['languages'])) {
            return $data;
        }

        $locale = $locale && Locales::exists($locale) ? $locale : self::DEFAULT_LOCALE;

        foreach ($data['languages'] as $key => $language) {
            $data['languages'][$key] = Languages::getName($language['name'], $locale);
        }

        return $data;
    }

    /**
     * @return array<array{name: string}>
     */
    private function makeLanguagesArray(AppTranslationCollection $translations): array
    {
        $languageIds = array_map(
            static fn ($translation) => $translation->getLanguageId(),
            $translations->getElements()
        );

        $translationLocales = $this->getLocalesCodesFromLanguageIds($languageIds);

        return array_map(
            static fn ($translationLocale) => ['name' => $translationLocale],
            $translationLocales
        );
    }

    /**
     * @param array<string, string> $translations
     */
    private function getTranslationFromArray(
        array $translations,
        string $currentLanguage,
        string $fallbackLanguage = self::DEFAULT_LOCALE
    ): ?string {
        if (isset($translations[$currentLanguage])) {
            return $translations[$currentLanguage];
        }

        if (isset($translations[$fallbackLanguage])) {
            return $translations[$fallbackLanguage];
        }

        return null;
    }
}
