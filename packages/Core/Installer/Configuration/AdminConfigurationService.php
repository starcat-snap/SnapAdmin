<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Configuration;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Controller\SystemConfigurationController;
use SnapAdmin\Core\Maintenance\User\Service\UserProvisioner;

/**
 * @internal
 *
 * @phpstan-import-type AdminUser from SystemConfigurationController
 */
#[Package('core')]
class AdminConfigurationService
{
    /**
     * @param AdminUser $user
     */
    public function createAdmin(array $user, Connection $connection): void
    {
        $userProvisioner = new UserProvisioner($connection);
        $userProvisioner->provision(
            $user['username'],
            $user['password'],
            [
                'firstName' => $user['firstName'],
                'lastName' => $user['lastName'],
                'email' => $user['email'],
            ]
        );
    }
}
