<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\DevOps\Environment\EnvironmentHelper;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;

/**
 * @internal
 */
#[Package('core')]
class KernelTest extends TestCase
{
    use KernelTestBehaviour;

    public function testDatabaseTimeZonesAreEqual(): void
    {
        $env = (bool)EnvironmentHelper::getVariable('SNAP_DBAL_TIMEZONE_SUPPORT_ENABLED', false);

        if ($env === false) {
            static::markTestSkipped('Database does not support timezones');
        }

        $c = $this->getContainer()->get(Connection::class);

        static::assertSame(
            $c->fetchOne('SELECT @@session.time_zone'),
            date_default_timezone_get()
        );
    }
}
