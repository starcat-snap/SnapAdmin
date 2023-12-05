<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Api;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Store\Api\StoreController;
use SnapAdmin\Core\Framework\Store\Exception\StoreApiException;
use SnapAdmin\Core\Framework\Store\Exception\StoreInvalidCredentialsException;
use SnapAdmin\Core\Framework\Store\Services\AbstractExtensionDataProvider;
use SnapAdmin\Core\Framework\Store\Services\StoreClient;
use SnapAdmin\Core\Framework\Store\Struct\PluginDownloadDataStruct;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\System\User\UserEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
class StoreControllerTest extends TestCase
{
    use IntegrationTestBehaviour;
    use KernelTestBehaviour;

    private Context $defaultContext;

    private EntityRepository $userRepository;

    protected function setUp(): void
    {
        $this->defaultContext = Context::createDefaultContext();
        $this->userRepository = $this->getContainer()->get('user.repository');
    }

    public function testCheckLoginWithoutStoreToken(): void
    {
        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $storeController = $this->getStoreController();
        $context = new Context(new AdminApiSource($adminUser->getId()));

        $response = $storeController->checkLogin($context)->getContent();
        static::assertIsString($response);

        $response = json_decode($response, true, 512, \JSON_THROW_ON_ERROR);
        static::assertNull($response['userInfo']);
    }

    public function testLoginWithCorrectCredentials(): void
    {
        $request = new Request([], [
            'snapId' => 'j.doe@snap.com',
            'password' => 'v3rys3cr3t',
        ]);

        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $context = new Context(new AdminApiSource($adminUser->getId()));

        $storeClientMock = $this->createMock(StoreClient::class);
        $storeClientMock->expects(static::once())
            ->method('loginWithSnapAdminId')
            ->with('j.doe@snap.com', 'v3rys3cr3t');

        $storeController = $this->getStoreController($storeClientMock);

        $response = $storeController->login($request, $context);

        static::assertInstanceOf(JsonResponse::class, $response);
        static::assertSame(200, $response->getStatusCode());
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $request = new Request([], [
            'snapId' => 'j.doe@snap.com',
            'password' => 'v3rys3cr3t',
        ]);

        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $context = new Context(new AdminApiSource($adminUser->getId()));

        $clientExceptionMock = $this->createMock(ClientException::class);
        $clientExceptionMock->method('getResponse')
            ->willReturn(new Response());

        $storeClientMock = $this->createMock(StoreClient::class);
        $storeClientMock->expects(static::once())
            ->method('loginWithSnapAdminId')
            ->willThrowException($clientExceptionMock);

        $storeController = $this->getStoreController($storeClientMock);

        static::expectException(StoreApiException::class);
        $storeController->login($request, $context);
    }

    public function testLoginWithInvalidCredentialsInput(): void
    {
        $request = new Request([], [
            'snapId' => null,
            'password' => null,
        ]);

        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $context = new Context(new AdminApiSource($adminUser->getId()));

        $storeClientMock = $this->createMock(StoreClient::class);
        $storeClientMock->expects(static::never())
            ->method('loginWithSnapAdminId');

        $storeController = $this->getStoreController($storeClientMock);

        static::expectException(StoreInvalidCredentialsException::class);
        $storeController->login($request, $context);
    }

    public function testCheckLoginWithStoreToken(): void
    {
        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $this->userRepository->update([[
            'id' => $adminUser->getId(),
            'storeToken' => 'store-token',
        ]], $this->defaultContext);

        $storeController = $this->getStoreController();
        $context = new Context(new AdminApiSource($adminUser->getId()));

        $response = $storeController->checkLogin($context)->getContent();
        static::assertIsString($response);

        $response = json_decode($response, true, 512, \JSON_THROW_ON_ERROR);
        static::assertEquals($response['userInfo'], [
            'name' => 'John Doe',
            'email' => 'john.doe@snap.com',
        ]);
    }

    public function testCheckLoginWithMultipleStoreTokens(): void
    {
        /** @var UserEntity $adminUser */
        $adminUser = $this->userRepository->search(new Criteria(), $this->defaultContext)->first();

        $this->userRepository->update([[
            'id' => $adminUser->getId(),
            'storeToken' => 'store-token',
            'firstName' => 'John',
        ]], $this->defaultContext);

        $this->userRepository->create([[
            'id' => Uuid::randomHex(),
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'storeToken' => 'store-token-two',
            'localeId' => $adminUser->getLocaleId(),
            'username' => 'admin-two',
            'password' => 's3cr3t12345',
            'email' => 'jane.doe@snap.com',
        ]], $this->defaultContext);

        $storeController = $this->getStoreController();
        $context = new Context(new AdminApiSource($adminUser->getId()));

        $response = $storeController->checkLogin($context)->getContent();
        static::assertIsString($response);

        $response = json_decode($response, true, 512, \JSON_THROW_ON_ERROR);
        static::assertEquals($response['userInfo'], [
            'name' => 'John Doe',
            'email' => 'john.doe@snap.com',
        ]);
    }

    private function getStoreController(
        ?StoreClient $storeClient = null,
    ): StoreController {
        return new StoreController(
            $storeClient ?? $this->getStoreClientMock(),
            $this->userRepository,
            $this->getContainer()->get(AbstractExtensionDataProvider::class)
        );
    }

    /**
     * @return StoreClient|MockObject
     */
    private function getStoreClientMock(): StoreClient
    {
        $storeClient = $this->getMockBuilder(StoreClient::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDownloadDataForPlugin', 'userInfo'])
            ->getMock();

        $storeClient->method('getDownloadDataForPlugin')
            ->willReturn($this->getPluginDownloadDataStub());

        $storeClient->method('userInfo')
            ->willReturn([
                'name' => 'John Doe',
                'email' => 'john.doe@snap.com',
            ]);

        return $storeClient;
    }

    private function getPluginDownloadDataStub(): PluginDownloadDataStruct
    {
        return (new PluginDownloadDataStruct())
            ->assign([
                'location' => 'not-null',
            ]);
    }
}
