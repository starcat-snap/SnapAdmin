<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\MessageQueue\Api;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Increment\AbstractIncrementer;
use SnapAdmin\Core\Framework\Increment\IncrementGatewayRegistry;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use SnapAdmin\Core\Framework\Test\MessageQueue\fixtures\TestTask;
use SnapAdmin\Core\Framework\Test\TestCaseBase\AdminFunctionalTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\QueueTestBehaviour;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[Package('system-settings')]
class ConsumeMessagesControllerTest extends TestCase
{
    use AdminFunctionalTestBehaviour;
    use QueueTestBehaviour;

    /**
     * @var AbstractIncrementer
     */
    private $gateway;

    protected function setUp(): void
    {
        $this->gateway = $this->getContainer()->get('snap.increment.gateway.registry')->get(IncrementGatewayRegistry::MESSAGE_QUEUE_POOL);
    }

    public function testConsumeMessages(): void
    {
        $connection = $this->getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM scheduled_task');

        // queue a task
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

        // consume the queued task
        $url = '/api/_action/message-queue/consume';
        $client = $this->getBrowser();
        $client->request('POST', $url, ['receiver' => 'async']);

        $response = json_decode((string)$client->getResponse()->getContent(), true, 512, \JSON_THROW_ON_ERROR);
        static::assertSame(200, $client->getResponse()->getStatusCode(), \print_r($response, true));
        static::assertArrayHasKey('handledMessages', $response);
        static::assertIsInt($response['handledMessages']);
        static::assertEquals(1, $response['handledMessages']);
    }
}
