<?php declare(strict_types=1);

namespace SwagManualMigrationTest\Migration;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
class Migration3 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 3;
    }

    public function update(Connection $connection): void
    {
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
