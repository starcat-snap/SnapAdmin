<?php declare(strict_types=1);

namespace SwagManualMigrationTest\Migration;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration4 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 4;
    }

    public function update(Connection $connection): void
    {
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
