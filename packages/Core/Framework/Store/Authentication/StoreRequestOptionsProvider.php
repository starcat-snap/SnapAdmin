<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Authentication;

use SnapAdmin\Core\Framework\Api\Context\Exception\InvalidContextSourceException;
use SnapAdmin\Core\Framework\Api\Context\Exception\InvalidContextSourceUserException;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\Services\InstanceService;
use SnapAdmin\Core\System\SystemConfig\SystemConfigService;
use SnapAdmin\Core\System\User\UserEntity;

/**
 * @internal
 */
#[Package('services-settings')]
class StoreRequestOptionsProvider extends AbstractStoreRequestOptionsProvider
{
    final public const CONFIG_KEY_STORE_LICENSE_DOMAIN = 'core.store.licenseHost';
    final public const CONFIG_KEY_STORE_SHOP_SECRET = 'core.store.shopSecret';

    private const SNAP_PLATFORM_TOKEN_HEADER = 'X-SnapAdmin-Platform-Token';
    private const SNAP_SHOP_SECRET_HEADER = 'X-SnapAdmin-Shop-Secret';

    public function __construct(
        private readonly EntityRepository    $userRepository,
        private readonly SystemConfigService $systemConfigService,
        private readonly InstanceService     $instanceService,
        private readonly LocaleProvider      $localeProvider,
    )
    {
    }

    /**
     * @return array<string, string>
     */
    public function getAuthenticationHeader(Context $context): array
    {
        return array_filter([
            self::SNAP_PLATFORM_TOKEN_HEADER => $this->getUserStoreToken($context),
            self::SNAP_SHOP_SECRET_HEADER => $this->systemConfigService->getString(self::CONFIG_KEY_STORE_SHOP_SECRET),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function getDefaultQueryParameters(Context $context): array
    {
        return [
            'snapVersion' => $this->instanceService->getSnapAdminVersion(),
            'language' => $this->localeProvider->getLocaleFromContext($context),
            'domain' => $this->getLicenseDomain(),
        ];
    }

    private function getUserStoreToken(Context $context): ?string
    {
        try {
            return $this->getTokenFromAdmin($context);
        } catch (InvalidContextSourceException) {
            return $this->getTokenFromSystem($context);
        }
    }

    private function getTokenFromAdmin(Context $context): ?string
    {
        $contextSource = $this->ensureAdminApiSource($context);
        $userId = $contextSource->getUserId();
        if ($userId == null) {
            throw new InvalidContextSourceUserException($contextSource::class);
        }

        return $this->fetchUserStoreToken(new Criteria([$userId]), $context);
    }

    private function getTokenFromSystem(Context $context): ?string
    {
        $contextSource = $context->getSource();
        if (!($contextSource instanceof SystemSource)) {
            throw new InvalidContextSourceException(SystemSource::class, $contextSource::class);
        }

        $criteria = new Criteria();
        $criteria->addFilter(
            new NotFilter(NotFilter::CONNECTION_OR, [new EqualsFilter('storeToken', null)])
        );

        return $this->fetchUserStoreToken($criteria, $context);
    }

    private function fetchUserStoreToken(Criteria $criteria, Context $context): ?string
    {
        /** @var UserEntity|null $user */
        $user = $this->userRepository->search($criteria, $context)->first();

        if ($user === null) {
            return null;
        }

        return $user->getStoreToken();
    }

    private function getLicenseDomain(): string
    {
        /** @var string $domain */
        $domain = $this->systemConfigService->get(self::CONFIG_KEY_STORE_LICENSE_DOMAIN) ?? '';

        return $domain;
    }
}
