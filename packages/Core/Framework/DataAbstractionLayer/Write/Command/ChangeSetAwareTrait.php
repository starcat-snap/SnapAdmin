<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
trait ChangeSetAwareTrait
{
    protected bool $requireChangeSet = false;

    protected ?ChangeSet $changeSet = null;

    public function requiresChangeSet(): bool
    {
        return $this->requireChangeSet;
    }

    public function requestChangeSet(): void
    {
        $this->requireChangeSet = true;
    }

    public function getChangeSet(): ?ChangeSet
    {
        return $this->changeSet;
    }

    public function setChangeSet(?ChangeSet $changeSet): void
    {
        $this->changeSet = $changeSet;
    }
}
