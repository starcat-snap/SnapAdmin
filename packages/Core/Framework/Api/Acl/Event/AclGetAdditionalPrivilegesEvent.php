<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Acl\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\NestedEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class AclGetAdditionalPrivilegesEvent extends NestedEvent
{
    public function __construct(
        private readonly Context $context,
        private array $privileges
    ) {
    }

    public function getPrivileges(): array
    {
        return $this->privileges;
    }

    public function setPrivileges(array $privileges): void
    {
        $this->privileges = $privileges;
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
