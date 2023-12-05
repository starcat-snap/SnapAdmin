<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\MessageQueue\Api;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskEntity;
use SnapAdmin\Core\Framework\Test\MessageQueue\fixtures\FooMessage;
use SnapAdmin\Core\Framework\Test\MessageQueue\fixtures\TestTask;
use SnapAdmin\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\QueueTestBehaviour;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[Package('system-settings')]
class ScheduledTaskControllerTest extends TestCase
{
    use AdminFunctionalTestBehaviour;
    use QueueTestBehaviour;

    public function testRunScheduledTasks(): void
    {
        $connection = $this->getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM scheduled_task');

        $repo = $this->getContainer()->get('scheduled_task.repository');
        $taskId = Uuid::randomHex();
        $repo->create([
            [
                'id' => $taskId,
                'name' => 'test',
                'scheduledTaskClass' => TestTask::class,
                'runInterval' => 300,
                'defaultRunInterval' => 300,
                'status' => ScheduledTaskDefinition::STATUS_SCHEDULED,
                'nextExecutionTime' => (new \DateTime())->modify('-1 second'),
            ],
        ], Context::createDefaultContext());

        $url = '/api/_action/scheduled-task/run';
        $client = $this->getBrowser();
        $client->request('POST', $url);

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(json_encode(['message' => 'Success']), $client->getResponse()->getContent());

        /** @var ScheduledTaskEntity $task */
        $task = $repo->search(new Criteria([$taskId]), Context::createDefaultContext())->get($taskId);
        static::assertEquals(ScheduledTaskDefinition::STATUS_QUEUED, $task->getStatus());
    }

    public function testRunSkippedTasks(): void
    {
        $connection = $this->getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM scheduled_task');

        $repo = $this->getContainer()->get('scheduled_task.repository');
        $taskId = Uuid::randomHex();
        $repo->create([
            [
                'id' => $taskId,
                'name' => 'test',
                'scheduledTaskClass' => TestTask::class,
                'runInterval' => 300,
                'defaultRunInterval' => 300,
                'status' => ScheduledTaskDefinition::STATUS_SKIPPED,
                'nextExecutionTime' => (new \DateTime())->modify('-1 second'),
            ],
        ], Context::createDefaultContext());

        $url = '/api/_action/scheduled-task/run';
        $client = $this->getBrowser();
        $client->request('POST', $url);

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(json_encode(['message' => 'Success']), $client->getResponse()->getContent());

        /** @var ScheduledTaskEntity $task */
        $task = $repo->search(new Criteria([$taskId]), Context::createDefaultContext())->get($taskId);
        static::assertEquals(ScheduledTaskDefinition::STATUS_QUEUED, $task->getStatus());
    }

    public function testGetMinRunInterval(): void
    {
        $connection = $this->getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM scheduled_task');

        $repo = $this->getContainer()->get('scheduled_task.repository');
        $nextExecutionTime = new \DateTime();
        $repo->create([
            [
                'name' => 'test',
                'scheduledTaskClass' => TestTask::class,
                'runInterval' => 5,
                'defaultRunInterval' => 5,
                'status' => ScheduledTaskDefinition::STATUS_SCHEDULED,
                'nextExecutionTime' => $nextExecutionTime,
            ],
            [
                'name' => 'test',
                'scheduledTaskClass' => FooMessage::class,
                'runInterval' => 200,
                'defaultRunInterval' => 200,
                'status' => ScheduledTaskDefinition::STATUS_FAILED,
                'nextExecutionTime' => $nextExecutionTime->modify('-10 seconds'),
            ],
        ], Context::createDefaultContext());

        $url = '/api/_action/scheduled-task/min-run-interval';
        $client = $this->getBrowser();
        $client->request('GET', $url);

        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame(json_encode(['minRunInterval' => 5]), $client->getResponse()->getContent());
    }
}
