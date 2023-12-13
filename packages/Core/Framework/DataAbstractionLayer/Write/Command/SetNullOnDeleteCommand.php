<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class SetNullOnDeleteCommand extends UpdateCommand
{
    /**
     * @param array<string, mixed> $payload
     * @param array<string> $primaryKey
     */
    public function __construct(
        EntityDefinition $definition,
        array $payload,
        array $primaryKey,
        EntityExistence $existence,
        string $path,
        private readonly bool $enforcedByConstraint
    ) {
        parent::__construct($definition, $payload, $primaryKey, $existence, $path);
    }

    public function isValid(): bool
    {
        // prevent execution if the constraint is enforced on DB level
        return !$this->enforcedByConstraint;
    }

    public function getPrivilege(): ?string
    {
        return null;
    }
}
