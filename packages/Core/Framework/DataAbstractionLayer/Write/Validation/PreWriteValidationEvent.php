<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Validation;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\DeleteCommand;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommand;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteContext;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteException;
use SnapAdmin\Core\Framework\Event\SnapAdminEvent;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class PreWriteValidationEvent extends Event implements SnapAdminEvent
{
    /**
     * @param list<WriteCommand> $commands
     */
    public function __construct(
        private readonly WriteContext $writeContext,
        private readonly array $commands
    ) {
    }

    public function getContext(): Context
    {
        return $this->writeContext->getContext();
    }

    public function getWriteContext(): WriteContext
    {
        return $this->writeContext;
    }

    /**
     * @return list<WriteCommand>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getExceptions(): WriteException
    {
        return $this->writeContext->getExceptions();
    }

    /**
     * @return list<array<string, string>>
     */
    public function getPrimaryKeys(string $entity): array
    {
        return $this->findPrimaryKeys($entity);
    }

    /**
     * @return list<array<string, string>>
     */
    public function getDeletedPrimaryKeys(string $entity): array
    {
        return $this->findPrimaryKeys($entity, fn (WriteCommand $command) => $command instanceof DeleteCommand);
    }

    /**
     * @return list<array<string, string>>
     */
    private function findPrimaryKeys(string $entity, ?\Closure $closure = null): array
    {
        $ids = [];

        foreach ($this->commands as $command) {
            if ($command->getEntityName() !== $entity) {
                continue;
            }

            if ($closure instanceof \Closure && !$closure($command)) {
                continue;
            }

            $ids[] = $command->getPrimaryKey();
        }

        return $ids;
    }
}
