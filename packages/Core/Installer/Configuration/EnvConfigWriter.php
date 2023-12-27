<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Configuration;

use Defuse\Crypto\Key;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Installer\Controller\SystemConfigurationController;
use SnapAdmin\Core\Installer\Finish\UniqueIdGenerator;
use SnapAdmin\Core\Maintenance\System\Struct\DatabaseConnectionInformation;

/**
 * @internal
 *
 * @phpstan-import-type Shop from SystemConfigurationController
 */
#[Package('core')]
class EnvConfigWriter
{
    private const FLEX_DOTENV = <<<'EOT'
###> symfony/lock ###
# Choose one of the stores below
# postgresql+advisory://db_user:db_password@localhost/db_name
LOCK_DSN=flock
###< symfony/lock ###

###> symfony/messenger ###
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=doctrine://default
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
###< symfony/messenger ###

###> symfony/mailer ###
# MAILER_DSN=null://null
###< symfony/mailer ###

###> snapadmin/core ###
APP_ENV=prod
APP_URL=http://127.0.0.1:8000
APP_SECRET=SECRET_PLACEHOLDER
INSTANCE_ID=INSTANCEID_PLACEHOLDER
BLUE_GREEN_DEPLOYMENT=0
DATABASE_URL=mysql://root:root@localhost/SnapAdmin
###< snapadmin/core ###

###> SnapAdmin/elasticsearch ###
OPENSEARCH_URL=http://localhost:9200
SnapAdmin_ES_ENABLED=0
SnapAdmin_ES_INDEXING_ENABLED=0
SnapAdmin_ES_INDEX_PREFIX=sw
SnapAdmin_ES_THROW_EXCEPTION=1
ADMIN_OPENSEARCH_URL=http://localhost:9200
SnapAdmin_ADMIN_ES_INDEX_PREFIX=sw-admin
SnapAdmin_ADMIN_ES_ENABLED=0
SnapAdmin_ADMIN_ES_REFRESH_INDICES=0
###< SnapAdmin/elasticsearch ###

###> SnapAdmin/storefront ###
STOREFRONT_PROXY_URL=http://localhost
SnapAdmin_HTTP_CACHE_ENABLED=1
SnapAdmin_HTTP_DEFAULT_TTL=7200
###< SnapAdmin/storefront ###
EOT;

    public function __construct(
        private readonly string $projectDir,
        private readonly UniqueIdGenerator $idGenerator
    ) {
    }

    public function writeConfig(DatabaseConnectionInformation $info): void
    {
        $uniqueId = $this->idGenerator->getUniqueId();
        $secret = Key::createNewRandomKey()->saveToAsciiSafeString();

        // Copy flex default .env if missing
        if (!file_exists($this->projectDir . '/.env')) {
            $template = str_replace(
                [
                    'SECRET_PLACEHOLDER',
                    'INSTANCEID_PLACEHOLDER',
                ],
                [
                    $secret,
                    $uniqueId,
                ],
                self::FLEX_DOTENV
            );
            file_put_contents($this->projectDir . '/.env', $template);
        }

        $newEnv = [];

        $newEnv[] = 'APP_SECRET=' . $secret;
        $newEnv[] = 'DATABASE_URL=' . $info->asDsn();

        if (!empty($info->getSslCaPath())) {
            $newEnv[] = 'DATABASE_SSL_CA=' . $info->getSslCaPath();
        }

        if (!empty($info->getSslCertPath())) {
            $newEnv[] = 'DATABASE_SSL_CERT=' . $info->getSslCertPath();
        }

        if (!empty($info->getSslCertKeyPath())) {
            $newEnv[] = 'DATABASE_SSL_KEY=' . $info->getSslCertKeyPath();
        }

        if ($info->getSslDontVerifyServerCert() !== null) {
            $newEnv[] = 'DATABASE_SSL_DONT_VERIFY_SERVER_CERT=' . ($info->getSslDontVerifyServerCert() ? '1' : '');
        }

        $newEnv[] = 'COMPOSER_HOME=' . $this->projectDir . '/var/cache/composer';
        $newEnv[] = 'INSTANCE_ID=' . $uniqueId;
        $newEnv[] = 'OPENSEARCH_URL=http://localhost:9200';
        $newEnv[] = 'ADMIN_OPENSEARCH_URL=http://localhost:9200';

        file_put_contents($this->projectDir . '/.env.local', implode("\n", $newEnv));

        $htaccessPath = $this->projectDir . '/public/.htaccess';

        if (file_exists($htaccessPath . '.dist') && !file_exists($htaccessPath)) {
            $perms = fileperms($htaccessPath . '.dist');
            copy($htaccessPath . '.dist', $htaccessPath);

            if ($perms) {
                chmod($htaccessPath, $perms | 0644);
            }
        }
    }
}
