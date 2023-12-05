<?php
declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity;

use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\App\Lifecycle\AbstractAppLoader;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;
use SnapAdmin\Core\System\CustomEntity\Schema\CustomEntityPersister;
use SnapAdmin\Core\System\CustomEntity\Schema\CustomEntitySchemaUpdater;
use SnapAdmin\Core\System\CustomEntity\Xml\Config\AdminUi\AdminUiXmlSchema;
use SnapAdmin\Core\System\CustomEntity\Xml\Config\CustomEntityEnrichmentService;
use SnapAdmin\Core\System\CustomEntity\Xml\CustomEntityXmlSchema;
use SnapAdmin\Core\System\CustomEntity\Xml\CustomEntityXmlSchemaValidator;
use Symfony\Component\Filesystem\Path;

/**
 * @internal
 */
#[Package('core')]
class CustomEntityLifecycleService
{
    public function __construct(
        private readonly CustomEntityPersister $customEntityPersister,
        private readonly CustomEntitySchemaUpdater $customEntitySchemaUpdater,
        private readonly CustomEntityEnrichmentService $customEntityEnrichmentService,
        private readonly CustomEntityXmlSchemaValidator $customEntityXmlSchemaValidator,
        private readonly string $projectDir,
        private readonly AbstractAppLoader $appLoader
    ) {
    }

    public function updatePlugin(string $pluginId, string $pluginPath): ?CustomEntityXmlSchema
    {
        return $this->update(
            sprintf(
                '%s/%s/src/Resources/',
                $this->projectDir,
                $pluginPath,
            ),
            PluginEntity::class,
            $pluginId
        );
    }

    public function updateApp(string $appId, string $appPath): ?CustomEntityXmlSchema
    {
        $resourcePath = $this->appLoader->locatePath($appPath, 'Resources');

        if ($resourcePath === null) {
            return null;
        }

        return $this->update(
            $resourcePath,
            AppEntity::class,
            $appId
        );
    }

    private function update(string $pathToCustomEntityFile, string $extensionEntityType, string $extensionId): ?CustomEntityXmlSchema
    {
        $customEntityXmlSchema = $this->getXmlSchema($pathToCustomEntityFile);
        if ($customEntityXmlSchema === null) {
            return null;
        }

        $customEntityXmlSchema = $this->customEntityEnrichmentService->enrich(
            $customEntityXmlSchema,
            $this->getAdminUiXmlSchema($pathToCustomEntityFile),
        );

        $this->customEntityPersister->update($customEntityXmlSchema->toStorage(), $extensionEntityType, $extensionId);
        $this->customEntitySchemaUpdater->update();

        return $customEntityXmlSchema;
    }

    private function getXmlSchema(string $pathToCustomEntityFile): ?CustomEntityXmlSchema
    {
        $filePath = Path::join($pathToCustomEntityFile, CustomEntityXmlSchema::FILENAME);
        if (!file_exists($filePath)) {
            return null;
        }

        $customEntityXmlSchema = CustomEntityXmlSchema::createFromXmlFile($filePath);
        $this->customEntityXmlSchemaValidator->validate($customEntityXmlSchema);

        return $customEntityXmlSchema;
    }

    private function getAdminUiXmlSchema(string $pathToCustomEntityFile): ?AdminUiXmlSchema
    {
        $configPath = Path::join($pathToCustomEntityFile, 'config', AdminUiXmlSchema::FILENAME);

        if (!file_exists($configPath)) {
            return null;
        }

        return AdminUiXmlSchema::createFromXmlFile($configPath);
    }
}
