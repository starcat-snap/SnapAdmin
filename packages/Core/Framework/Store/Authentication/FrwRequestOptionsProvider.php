<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Authentication;

use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Api\Context\Exception\InvalidContextSourceException;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\Aggregate\UserConfig\UserConfigEntity;

/**
 * @internal
 */
#[Package('services-settings')]
class FrwRequestOptionsProvider extends AbstractStoreRequestOptionsProvider
{
    private const SNAP_TOKEN_HEADER = 'X-SnapAdmin-Token';

    public function __construct(
        private readonly AbstractStoreRequestOptionsProvider $optionsProvider,
        private readonly EntityRepository                    $userConfigRepository,
    )
    {
    }

    public function getAuthenticationHeader(Context $context): array
    {
        return array_filter([self::SNAP_TOKEN_HEADER => $this->getFrwUserToken($context)]);
    }

    public function getDefaultQueryParameters(Context $context): array
    {
        return $this->optionsProvider->getDefaultQueryParameters($context);
    }

    private function getFrwUserToken(Context $context): ?string
    {
        if (!$context->getSource() instanceof AdminApiSource) {
            throw new InvalidContextSourceException(AdminApiSource::class, $context->getSource()::class);
        }

        /** @var AdminApiSource $contextSource */
        $contextSource = $context->getSource();

        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('userId', $contextSource->getUserId())
        );


        return null;
    }
}
