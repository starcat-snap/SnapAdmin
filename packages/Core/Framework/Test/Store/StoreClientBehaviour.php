<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store;

use GuzzleHttp\Handler\MockHandler;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\Kernel;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use SnapAdmin\Core\System\User\UserCollection;
use SnapAdmin\Core\System\User\UserEntity;

/**
 * @internal
 */
#[Package('services-settings')]
trait StoreClientBehaviour
{
    public function getStoreRequestHandler(): MockHandler
    {
        /** @var MockHandler $handler */
        $handler = $this->getContainer()->get('snap.store.mock_handler');

        return $handler;
    }

    public function getFrwRequestHandler(): MockHandler
    {
        /** @var MockHandler $handler */
        $handler = $this->getContainer()->get('snap.frw.mock_handler');

        return $handler;
    }

    #[After]
    #[Before]
    public function resetStoreMock(): void
    {
        $this->getStoreRequestHandler()->reset();
    }

    #[After]
    #[Before]
    public function resetFrwMock(): void
    {
        $this->getFrwRequestHandler()->reset();
    }

    protected function createAdminStoreContext(): Context
    {
        $userId = Uuid::randomHex();
        $storeToken = Uuid::randomHex();

        $data = [
            [
                'id' => $userId,
                'localeId' => $this->getLocaleIdOfSystemLanguage(),
                'username' => 'foobar',
                'password' => 'asdasdasdasd',
                'name' => 'Foo',
                'nickName' => 'Bar',
                'userNumber' => '1234556',
                'email' => Uuid::randomHex() . '@bar.com',
                'storeToken' => $storeToken,
            ],
        ];

        $this->getUserRepository()->create($data, Context::createDefaultContext());

        $source = new AdminApiSource($userId);
        $source->setIsAdmin(true);

        return Context::createDefaultContext($source);
    }

    protected function getStoreTokenFromContext(Context $context): string
    {
        /** @var AdminApiSource $source */
        $source = $context->getSource();

        $userId = $source->getUserId();

        if ($userId === null) {
            throw new \RuntimeException('No user id found in context');
        }

        /** @var UserCollection $users */
        $users = $this->getUserRepository()->search(new Criteria([$userId]), $context)->getEntities();

        if ($users->count() === 0) {
            throw new \RuntimeException('No user found with id ' . $userId);
        }

        $user = $users->first();
        static::assertInstanceOf(UserEntity::class, $user);

        $token = $user->getStoreToken();
        static::assertIsString($token);

        return $token;
    }

    protected function setLicenseDomain(?string $licenseDomain): void
    {
        $systemConfigService = $this->getContainer()->get(SystemConfigService::class);

        $systemConfigService->set(
            'core.store.licenseHost',
            $licenseDomain
        );
    }

    protected function setShopSecret(string $shopSecret): void
    {
        $systemConfigService = $this->getContainer()->get(SystemConfigService::class);

        $systemConfigService->set(
            'core.store.shopSecret',
            $shopSecret
        );
    }

    protected function getSnapAdminVersion(): string
    {
        $version = $this->getContainer()->getParameter('kernel.snap_version');

        return $version === Kernel::SNAP_FALLBACK_VERSION ? '___VERSION___' : $version;
    }

    protected function getUserRepository(): EntityRepository
    {
        return $this->getContainer()->get('user.repository');
    }
}
