<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Logging;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Monolog\Handler\TestHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\LoggingService;
use SnapAdmin\Core\Framework\Test\Logging\Event\LogAwareTestFlowEvent;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

/**
 * @internal
 */
class LoggingServiceTest extends TestCase
{
    use IntegrationTestBehaviour;

    /**
     * @var Context
     */
    protected $context;

    protected function setUp(): void
    {
        parent::setUp();

        $this->context = Context::createDefaultContext();
    }

    /**
     * @throws Exception
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        $connection = $this->getContainer()->get(Connection::class);
        $connection->executeStatement('DELETE FROM `log_entry`');
    }

}
