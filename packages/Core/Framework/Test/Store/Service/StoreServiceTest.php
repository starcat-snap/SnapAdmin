<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Store\Service;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Store\Services\StoreService;
use SnapAdmin\Core\Framework\Store\Struct\AccessTokenStruct;
use SnapAdmin\Core\Framework\Store\Struct\ShopUserTokenStruct;
use SnapAdmin\Core\Framework\Test\Store\StoreClientBehaviour;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

/**
 * @internal
 */
class StoreServiceTest extends TestCase
{
    use IntegrationTestBehaviour;
    use StoreClientBehaviour;

    private StoreService $storeService;

    protected function setUp(): void
    {
        $this->storeService = $this->getContainer()->get(StoreService::class);
    }

    public function testUpdateStoreToken(): void
    {
        $adminStoreContext = $this->createAdminStoreContext();

        $newToken = 'updated-store-token';
        $accessTokenStruct = new AccessTokenStruct(
            new ShopUserTokenStruct(
                $newToken,
                new \DateTimeImmutable()
            )
        );

        $this->storeService->updateStoreToken(
            $adminStoreContext,
            $accessTokenStruct
        );

        /** @var AdminApiSource $adminSource */
        $adminSource = $adminStoreContext->getSource();
        /** @var string $userId */
        $userId = $adminSource->getUserId();
        $criteria = new Criteria([$userId]);

        $updatedUser = $this->getUserRepository()->search($criteria, $adminStoreContext)->first();

        static::assertEquals('updated-store-token', $updatedUser->getStoreToken());
    }
}
