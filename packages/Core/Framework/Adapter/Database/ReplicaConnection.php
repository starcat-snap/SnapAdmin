<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Database;

use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Kernel;

/**
 * @internal
 */
#[Package('core')]
class ReplicaConnection
{
    public static function ensurePrimary(): void
    {
        $connection = Kernel::getConnection();

        if ($connection instanceof PrimaryReadReplicaConnection) {
            $connection->ensureConnectedToPrimary();
        }
    }
}
