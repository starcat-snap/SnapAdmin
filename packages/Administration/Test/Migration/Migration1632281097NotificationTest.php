<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Test\Migration;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Administration\Migration\V6_6\Migration1632281097Notification;
use SnapAdmin\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;

/**
 * @internal
 */
class Migration1632281097NotificationTest extends TestCase
{
    use KernelTestBehaviour;

    public function testNoNotificationTable(): void
    {
        $conn = $this->getContainer()->get(Connection::class);
        $conn->executeStatement('DROP TABLE `notification`');

        $migration = new Migration1632281097Notification();
        $migration->update($conn);
        $exists = $conn->fetchOne('SELECT COUNT(*) FROM `notification`') !== false;

        static::assertTrue($exists);
    }
}
