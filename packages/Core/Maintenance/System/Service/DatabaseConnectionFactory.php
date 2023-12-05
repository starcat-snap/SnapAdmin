<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\System\Service;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Maintenance\System\Exception\DatabaseSetupException;
use SnapAdmin\Core\Maintenance\System\Struct\DatabaseConnectionInformation;

#[Package('core')]
class DatabaseConnectionFactory
{
    /**
     * non-static implementation of createConnection(), can be mocked in tests
     */
    public function getConnection(DatabaseConnectionInformation $connectionInformation, bool $withoutDatabase = false): Connection
    {
        return self::createConnection($connectionInformation, $withoutDatabase);
    }

    public static function createConnection(DatabaseConnectionInformation $connectionInformation, bool $withoutDatabase = false): Connection
    {
        $connection = DriverManager::getConnection($connectionInformation->toDBALParameters($withoutDatabase), new Configuration());

        self::checkVersion($connection);

        return $connection;
    }

    private static function checkVersion(Connection $connection): void
    {
        // https://developer.snap.com/docs/guides/installation/overview#system-requirements
        $mysqlRequiredVersion = '5.7.21';
        $mariaDBRequiredVersion = '10.3.22';

        $version = $connection->fetchOne('SELECT VERSION()');
        \assert(\is_string($version));
        if (\mb_stripos($version, 'mariadb') !== false) {
            if (version_compare($version, $mariaDBRequiredVersion, '<')) {
                throw new DatabaseSetupException(sprintf(
                    'Your database server is running MariaDB %s, but SnapAdmin 6 requires at least MariaDB %s OR MySQL %s',
                    $version,
                    $mariaDBRequiredVersion,
                    $mysqlRequiredVersion
                ));
            }

            return;
        }

        if (version_compare($version, $mysqlRequiredVersion, '<')) {
            throw new DatabaseSetupException(sprintf(
                'Your database server is running MySQL %s, but SnapAdmin 6 requires at least MySQL %s OR MariabDB %s',
                $version,
                $mysqlRequiredVersion,
                $mariaDBRequiredVersion
            ));
        }
    }
}
