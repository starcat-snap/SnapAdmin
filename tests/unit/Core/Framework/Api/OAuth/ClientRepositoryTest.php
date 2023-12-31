<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Core\Framework\Api\OAuth;

use Doctrine\DBAL\Connection;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Api\OAuth\Client\ApiClient;
use SnapAdmin\Core\Framework\Api\OAuth\ClientRepository;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[CoversClass(ClientRepository::class)]
class ClientRepositoryTest extends TestCase
{
    private ClientRepository $clientRepository;

    private Connection&MockObject $connection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = $this->createMock(Connection::class);
        $this->clientRepository = new ClientRepository($this->connection);
    }

    public function testValidateClientWithInvalidGrantTypeThrowException(): void
    {
        static::expectExceptionMessage('The authorization grant type is not supported by the authorization server.');
        $this->clientRepository->validateClient('clientIdentifier', 'clientSecret', 'unsupportGrantType');
    }

    /**
     * @param string $clientIdentifier
     */
    #[DataProvider('validateClientDataProvider')]
    public function testValidateClient(string $grantType, $clientIdentifier, string $clientSecret, bool $expectedResult): void
    {
        $this->connection->method('fetchAssociative')->willReturnCallback(function () use ($clientIdentifier, $clientSecret) {
            if ($clientIdentifier === 'SWUAADMIN' && $clientSecret === 'SnapAdmin') {
                return [
                    'secret_access_key' => password_hash($clientSecret, \PASSWORD_BCRYPT),
                ];
            }

            return false;
        });

        $result = $this->clientRepository->validateClient($clientIdentifier, $clientSecret, $grantType);
        static::assertSame($expectedResult, $result);
    }

    #[DataProvider('getClientEntityDataProvider')]
    public function testGetClientEntity(mixed $clientIdentifier, ?ClientEntityInterface $expectedResult): void
    {
        $this->connection->method('fetchAssociative')->willReturnCallback(function () use ($clientIdentifier) {
            if ($clientIdentifier === 'SWUAUSERCORRECT') {
                return [
                    'user_id' => Uuid::randomBytes(),
                    'secret_access_key' => 'secret_access_key',
                ];
            }

            return false;
        });

        $clientEntity = $this->clientRepository->getClientEntity($clientIdentifier);

        if (!$expectedResult instanceof ClientEntityInterface) {
            static::assertNull($clientEntity);

            return;
        }

        static::assertNotNull($clientEntity);
        static::assertInstanceOf(ApiClient::class, $clientEntity);
        static::assertSame($expectedResult->getIdentifier(), $clientEntity->getIdentifier());
    }

    /**
     * @return iterable<string, array<mixed>>
     */
    public static function validateClientDataProvider(): iterable
    {
        yield 'password grant type' => ['password', 'administration', 'SnapAdmin', true];
        yield 'refresh_token grant type' => ['refresh_token', 'administration', 'SnapAdmin', true];
        yield 'client_credentials grant type with invalid clientIdentifier' => ['client_credentials', true, 'SnapAdmin', false];
        yield 'client_credentials grant type with incorrect clientIdentifier' => ['client_credentials', 'SWUAJOHNDOE', 'SnapAdmin', false];
        yield 'client_credentials grant type with correct clientIdentifier' => ['client_credentials', 'SWUAADMIN', 'SnapAdmin', true];
    }

    /**
     * @return iterable<string, array<mixed>>
     */
    public static function getClientEntityDataProvider(): iterable
    {
        yield 'null clientIdentifier' => [null, null];
        yield 'bool clientIdentifier' => [false, null];
        yield 'user origin clientIdentifier' => ['SWUAUSERCORRECT', new ApiClient('SWUAUSERCORRECT', true, 'foo')];
        yield 'user origin clientIdentifier invalid' => ['SWUAUSERINVALID', null];
        yield 'administration clientIdentifier' => ['administration', new ApiClient('administration', true)];
    }
}
