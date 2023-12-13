<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\JsonSerializableTrait;

#[Package('core')]
class AdminApiSource implements ContextSource, \JsonSerializable
{
    use JsonSerializableTrait;

    public string $type = 'admin-api';

    private bool $isAdmin;

    /**
     * @var array<string>
     */
    private array $permissions = [];

    public function __construct(
        private readonly string $userId
    ) {
        $this->isAdmin = false;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function setIsAdmin(bool $isAdmin): void
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * @param array<string> $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function isAllowed(string $privilege): bool
    {
        if ($this->isAdmin) {
            return true;
        }

        return \in_array($privilege, $this->permissions, true);
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }
}
