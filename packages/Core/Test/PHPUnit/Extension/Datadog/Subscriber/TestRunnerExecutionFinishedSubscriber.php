<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\PHPUnit\Extension\Datadog\Subscriber;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Test\PHPUnit\Extension\Datadog\DatadogPayload;
use SnapAdmin\Core\Test\PHPUnit\Extension\Datadog\DatadogPayloadCollection;
use SnapAdmin\Core\Test\PHPUnit\Extension\Datadog\Gateway\DatadogGateway;

/**
 * @internal
 */
#[Package('core')]
class TestRunnerExecutionFinishedSubscriber implements ExecutionFinishedSubscriber
{
    public function __construct(
        private readonly DatadogPayloadCollection $failedTests,
        private readonly DatadogPayloadCollection $slowTests,
        private readonly DatadogGateway $gateway
    ) {
    }

    public function notify(ExecutionFinished $event): void
    {
        $failedTests = $this->failedTests->map(fn (DatadogPayload $payload) => $payload->serialize());
        $slowTests = $this->slowTests->map(fn (DatadogPayload $payload) => $payload->serialize());

        $this->gateway->sendLogs(array_merge($failedTests, $slowTests));
    }
}
