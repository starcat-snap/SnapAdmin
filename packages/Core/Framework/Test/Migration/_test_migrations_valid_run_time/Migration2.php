<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Migration\_test_migrations_valid_run_time;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration2 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 2;
    }

    public function update(Connection $connection): void
    {
        // nth
    }

    public function updateDestructive(Connection $connection): void
    {
        // nth
    }
}
