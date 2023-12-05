<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write;

use SnapAdmin\Core\Framework\Api\Sync\SyncOperation;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityWriteResult;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal use entity repository to write data
 */
#[Package('core')]
interface EntityWriterInterface
{
    /**
     * @param list<SyncOperation> $operations
     */
    public function sync(array $operations, WriteContext $context): WriteResult;

    /**
     * @param array<array<string, mixed>> $rawData
     *
     * @return array<string, array<EntityWriteResult>>
     */
    public function upsert(EntityDefinition $definition, array $rawData, WriteContext $writeContext): array;

    /**
     * @param array<array<string, mixed>> $rawData
     *
     * @return array<string, array<EntityWriteResult>>
     */
    public function insert(EntityDefinition $definition, array $rawData, WriteContext $writeContext);

    /**
     * @param array<array<string, mixed>> $rawData
     *
     * @return array<string, array<EntityWriteResult>>
     */
    public function update(EntityDefinition $definition, array $rawData, WriteContext $writeContext);

    /**
     * @param array<array<string, string>> $ids
     */
    public function delete(EntityDefinition $definition, array $ids, WriteContext $writeContext): WriteResult;
}
