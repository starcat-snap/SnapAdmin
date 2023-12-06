<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\Scheduler;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\MessageQueueException;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskCollection;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskEntity;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

/**
 * @internal
 */
#[Package('core')]
class TaskRunner
{
    /**
     * @param iterable<object> $taskHandler
     * @param EntityRepository<ScheduledTaskCollection> $scheduledTaskRepository
     */
    public function __construct(
        private readonly iterable $taskHandler,
        private readonly EntityRepository $scheduledTaskRepository,
    ) {
    }

    public function runSingleTask(string $taskName, Context $context): void
    {
        $scheduledTask = $this->fetchTask($taskName, $context);

        // Set status to allow running it
        $this->scheduledTaskRepository->update([
            [
                'id' => $scheduledTask->getId(),
                'status' => ScheduledTaskDefinition::STATUS_QUEUED,
                'nextExecutionTime' => new \DateTime(),
            ],
        ], $context);

        // Create task
        /** @var class-string<ScheduledTask> $className */
        $className = $scheduledTask->getScheduledTaskClass();
        $task = new $className();
        $task->setTaskId($scheduledTask->getId());

        foreach ($this->taskHandler as $handler) {
            if (!$handler instanceof ScheduledTaskHandler) {
                continue;
            }

            $reflection = new \ReflectionClass($handler);
            $asMessage = $reflection->getAttributes(AsMessageHandler::class);

            if ($asMessage === []) {
                continue;
            }

            foreach ($asMessage as $attribute) {
                /** @var AsMessageHandler $messageAttribute */
                $messageAttribute = $attribute->newInstance();

                if ($messageAttribute->handles === $className) {
                    // calls the __invoke() method of the abstract ScheduledTaskHandler
                    $handler($task);

                    return;
                }
            }
        }
    }

    private function fetchTask(string $taskName, Context $context): ScheduledTaskEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $taskName));

        /** @var ScheduledTaskEntity|null $task */
        $task = $this->scheduledTaskRepository->search($criteria, $context)->first();

        if ($task === null) {
            throw MessageQueueException::cannotFindTaskByName($taskName);
        }

        return $task;
    }
}
