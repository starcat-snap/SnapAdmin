<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Config;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\Xml\Config\AdminUi\AdminUiXmlSchema;
use SnapAdmin\Core\System\CustomEntity\Xml\Config\AdminUi\AdminUiXmlSchemaValidator;
use SnapAdmin\Core\System\CustomEntity\Xml\Config\CmsAware\CmsAwareFields;
use SnapAdmin\Core\System\CustomEntity\Xml\CustomEntityXmlSchema;

/**
 * @internal
 */
#[Package('content')]
class CustomEntityEnrichmentService
{
    public function __construct(private readonly AdminUiXmlSchemaValidator $adminUiXmlSchemaValidator)
    {
    }

    public function enrich(
        CustomEntityXmlSchema $customEntityXmlSchema,
        ?AdminUiXmlSchema $adminUiXmlSchema
    ): CustomEntityXmlSchema {


        if ($adminUiXmlSchema !== null) {
            $customEntityXmlSchema = $this->enrichAdminUi($customEntityXmlSchema, $adminUiXmlSchema);
        }

        return $customEntityXmlSchema;
    }

    private function enrichAdminUi(CustomEntityXmlSchema $customEntityXmlSchema, AdminUiXmlSchema $adminUiXmlSchema): CustomEntityXmlSchema
    {
        $adminUiEntitiesConfig = $adminUiXmlSchema->getAdminUi()?->getEntities();
        if ($adminUiEntitiesConfig === null) {
            return $customEntityXmlSchema;
        }

        foreach ($customEntityXmlSchema->getEntities()?->getEntities() ?? [] as $entity) {
            if (!\array_key_exists($entity->getName(), $adminUiEntitiesConfig)) {
                continue;
            }

            $this->adminUiXmlSchemaValidator->validateConfigurations(
                $adminUiEntitiesConfig[$entity->getName()],
                $entity
            );

            $flags = [...$entity->getFlags(), ...['admin-ui' => $adminUiEntitiesConfig[$entity->getName()]]];
            $entity->setFlags($flags);

            unset($adminUiEntitiesConfig[$entity->getName()]);
        }

        if (!empty($adminUiEntitiesConfig)) {
            throw CustomEntityConfigurationException::entityNotGiven(
                AdminUiXmlSchema::FILENAME,
                array_keys($adminUiEntitiesConfig)
            );
        }

        return $customEntityXmlSchema;
    }
}
