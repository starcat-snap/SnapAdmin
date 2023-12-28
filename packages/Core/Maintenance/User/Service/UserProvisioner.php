<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\User\Service;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\PasswordFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Random;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\System\NumberRange\ValueGenerator\NumberRangeValueGeneratorInterface;

#[Package('core')]
class UserProvisioner
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Connection $connection,
        private readonly NumberRangeValueGeneratorInterface $numberRangeValueGenerator
    ) {
    }

    /**
     * @param array{phone?: string, name?: string, email?: string, localeId?: string, admin?: bool, nickName?:string} $additionalData
     */
    public function provision(string $username, ?string $password = null, array $additionalData = []): string
    {
        if ($this->userExists($username)) {
            throw new \RuntimeException(sprintf('User with username "%s" already exists.', $username));
        }

        $minPasswordLength = $this->getAdminPasswordMinLength();

        if ($password && \strlen($password) < $minPasswordLength) {
            throw new \InvalidArgumentException(sprintf('The password length cannot be shorter than %d characters.', $minPasswordLength));
        }

        $password = $password ?? Random::getAlphanumericString(max($minPasswordLength, 8));

        $userPayload = [
            'id' => Uuid::randomBytes(),
            'phone' => $additionalData['phone'] ?? '',
            'name' => $additionalData['name'] ?? '',
            'email' => $additionalData['email'] ?? 'info@snap.com',
            'username' => $username,
            'password' => password_hash($password, \PASSWORD_BCRYPT),
            'locale_id' => $additionalData['localeId'] ?? $this->getLocaleOfSystemLanguage(),
            'active' => true,
            'nick_name' => $additionalData['nickName'] ?? $username,
            'admin' => $additionalData['admin'] ?? true,
            'user_number' => $this->numberRangeValueGenerator->getValue('user', Context::createDefaultContext()),
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ];

        $this->connection->insert('user', $userPayload);

        return $password;
    }

    private function userExists(string $username): bool
    {
        $builder = $this->connection->createQueryBuilder();

        return $builder->select('1')
                ->from('user')
                ->where('username = :username')
                ->setParameter('username', $username)
                ->executeQuery()
                ->rowCount() > 0;
    }

    private function getLocaleOfSystemLanguage(): string
    {
        $builder = $this->connection->createQueryBuilder();

        return (string) $builder->select('locale.id')
            ->from('language', 'language')
            ->innerJoin('language', 'locale', 'locale', 'language.locale_id = locale.id')
            ->where('language.id = :id')
            ->setParameter('id', Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM))
            ->executeQuery()
            ->fetchOne();
    }

    private function getAdminPasswordMinLength(): int
    {
        $configKey = PasswordFieldSerializer::CONFIG_MIN_LENGTH_FOR[PasswordField::FOR_ADMIN];

        $result = $this->connection->fetchOne(
            'SELECT configuration_value FROM system_config WHERE configuration_key = :configKey;',
            [
                'configKey' => $configKey,
            ]
        );

        if ($result === false) {
            return 0;
        }

        $config = json_decode($result, true);

        return $config['_value'] ?? 0;
    }
}
