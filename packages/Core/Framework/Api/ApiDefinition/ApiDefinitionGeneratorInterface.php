<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\ApiDefinition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\Entity\SalesChannelDefinitionInterface;

/**
 * @internal
 *
 * @phpstan-import-type Api from DefinitionService
 * @phpstan-import-type ApiType from DefinitionService
 * @phpstan-import-type OpenApiSpec from DefinitionService
 * @phpstan-import-type ApiSchema from DefinitionService
 */
#[Package('core')]
interface ApiDefinitionGeneratorInterface
{
    public function supports(string $format, string $api): bool;

    /**
     * @param array<string, EntityDefinition>|array<string, EntityDefinition&SalesChannelDefinitionInterface> $definitions
     * @param Api $api
     * @param ApiType $apiType
     *
     * @return OpenApiSpec
     */
    public function generate(array $definitions, string $api, string $apiType, ?string $bundleName): array;

    /**
     * @param array<string, EntityDefinition>|array<string, EntityDefinition&SalesChannelDefinitionInterface> $definitions
     *
     * @return ApiSchema
     */
    public function getSchema(array $definitions): array;
}
