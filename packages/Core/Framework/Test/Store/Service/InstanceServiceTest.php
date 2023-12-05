<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Service;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Store\Services\InstanceService;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
class InstanceServiceTest extends TestCase
{
    public function testItReturnsInstanceIdIfNull(): void
    {
        $instanceService = new InstanceService(
            '6.4.0.0',
            null
        );

        static::assertNull($instanceService->getInstanceId());
    }

    public function testItReturnsInstanceIdIfSet(): void
    {
        $instanceService = new InstanceService(
            '6.4.0.0',
            'i-am-unique'
        );

        static::assertEquals('i-am-unique', $instanceService->getInstanceId());
    }

    public function testItReturnsSpecificSnapAdminVersion(): void
    {
        $instanceService = new InstanceService(
            '6.1.0.0',
            null
        );

        static::assertEquals('6.1.0.0', $instanceService->getSnapAdminVersion());
    }

    public function testItReturnsSnapAdminVersionStringIfVersionIsDeveloperVersion(): void
    {
        $instanceService = new InstanceService(
            Kernel::SNAP_FALLBACK_VERSION,
            null
        );

        static::assertEquals('___VERSION___', $instanceService->getSnapAdminVersion());
    }
}
