<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
abstract class EntityProtection
{
    /**
     * Returns a readable name for the flag
     */
    abstract public function parse(): \Generator;

    /**
     * Can be overriden if protection is aware of different scopes
     */
    public function isAllowed(string $scope): bool
    {
        return true;
    }
}
