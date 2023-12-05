<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\MappingEntityClassesException;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
abstract class MappingEntityDefinition extends EntityDefinition
{
    public function getCollectionClass(): string
    {
        throw new MappingEntityClassesException();
    }

    public function getEntityClass(): string
    {
        throw new MappingEntityClassesException();
    }

    protected function getBaseFields(): array
    {
        return [];
    }

    protected function defaultFields(): array
    {
        return [];
    }
}
