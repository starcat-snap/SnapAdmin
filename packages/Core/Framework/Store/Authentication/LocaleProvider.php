<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Authentication;

use SnapAdmin\Core\Framework\Api\Context\AdminApiSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\EntityNotFoundException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Locale\LocaleEntity;
use SnapAdmin\Core\System\User\UserDefinition;

/**
 * @internal
 */
#[Package('services-settings')]
class LocaleProvider
{
    public function __construct(private readonly EntityRepository $userRepository)
    {
    }

    public function getLocaleFromContext(Context $context): string
    {
        if (!$context->getSource() instanceof AdminApiSource) {
            return 'zh-CN';
        }

        /** @var AdminApiSource $source */
        $source = $context->getSource();

        if ($source->getUserId() === null) {
            return 'zh-CN';
        }

        $criteria = new Criteria([$source->getUserId()]);
        $criteria->addAssociation('locale');

        $user = $this->userRepository->search($criteria, $context)->first();

        if ($user === null) {
            throw new EntityNotFoundException(UserDefinition::ENTITY_NAME, $source->getUserId());
        }

        /** @var LocaleEntity $locale */
        $locale = $user->getLocale();

        return $locale->getCode();
    }
}
