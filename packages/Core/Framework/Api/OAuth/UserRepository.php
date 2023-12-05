<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\OAuth;

use Doctrine\DBAL\Connection;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use SnapAdmin\Core\Framework\Api\OAuth\User\User;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;

#[Package('core')]
class UserRepository implements UserRepositoryInterface
{
    /**
     * @internal
     */
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface
    {
        $builder = $this->connection->createQueryBuilder();
        $user = $builder->select(['user.id', 'user.password'])
            ->from('user')
            ->where('username = :username')
            ->setParameter('username', $username)
            ->executeQuery()
            ->fetchAssociative();

        if (!$user) {
            return null;
        }

        if (!password_verify($password, (string)$user['password'])) {
            return null;
        }

        return new User(Uuid::fromBytesToHex($user['id']));
    }
}
