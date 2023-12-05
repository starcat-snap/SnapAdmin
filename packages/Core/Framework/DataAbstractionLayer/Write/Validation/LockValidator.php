<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Validation;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\InsertCommand;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommand;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Validation\WriteConstraintViolationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * @internal
 */
#[Package('core')]
class LockValidator implements EventSubscriberInterface
{
    final public const VIOLATION_LOCKED = 'FRAMEWORK__ENTITY_IS_LOCKED';

    /**
     * @internal
     */
    public function __construct(private readonly Connection $connection)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PreWriteValidationEvent::class => 'preValidate',
        ];
    }

    /**
     * @throws WriteConstraintViolationException
     */
    public function preValidate(PreWriteValidationEvent $event): void
    {
        $violations = new ConstraintViolationList();
        $writeCommands = $event->getCommands();
        $lockedEntities = $this->containsLockedEntities($writeCommands);

        if (empty($lockedEntities)) {
            return;
        }

        $message = 'The %s entity is locked and can neither be modified nor deleted.';

        foreach ($lockedEntities as $entity => $_isLocked) {
            $violations->add(new ConstraintViolation(
                sprintf($message, $entity),
                sprintf($message, '{{ entity }}'),
                ['{{ entity }}' => $entity],
                null,
                '/',
                null,
                null,
                self::VIOLATION_LOCKED
            ));
        }

        $event->getExceptions()->add(new WriteConstraintViolationException($violations));
    }

    /**
     * @param WriteCommand[] $writeCommands
     *
     * @return array<string, bool>
     */
    private function containsLockedEntities(array $writeCommands): array
    {
        $ids = [];
        $locked = [];

        foreach ($writeCommands as $command) {
            if ($command instanceof InsertCommand) {
                continue;
            }

            if (!$command->getDefinition()->isLockAware()) {
                continue;
            }

            $ids[$command->getDefinition()->getEntityName()][] = $command->getPrimaryKey()['id'];
        }

        /** @var string $entityName */
        foreach ($ids as $entityName => $primaryKeys) {
            $locked[$entityName] = $this->connection->createQueryBuilder()
                ->select('1')
                ->from(EntityDefinitionQueryHelper::escape($entityName))
                ->where('`id` IN (:ids) AND `locked` = 1')
                ->setParameter('ids', $primaryKeys, ArrayParameterType::BINARY)
                ->executeQuery()
                ->rowCount() > 0;
        }

        return array_filter($locked);
    }
}
