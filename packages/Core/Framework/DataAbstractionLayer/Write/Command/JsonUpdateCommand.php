<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class JsonUpdateCommand extends UpdateCommand
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string> $primaryKey
     */
    public function __construct(
        EntityDefinition $definition,
        private readonly string $storageName,
        array $payload,
        array $primaryKey,
        EntityExistence $existence,
        string $path
    ) {
        parent::__construct($definition, $payload, $primaryKey, $existence, $path);
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }
}
