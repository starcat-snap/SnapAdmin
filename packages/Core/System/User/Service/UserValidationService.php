<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Service;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Exception\InconsistentCriteriaIdsException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\NotFilter;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
class UserValidationService
{
    /**
     * @internal
     */
    public function __construct(private readonly EntityRepository $userRepo)
    {
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function checkEmailUnique(string $userEmail, string $userId, Context $context): bool
    {
        $criteria = new Criteria();

        $criteria->addFilter(
            new MultiFilter(
                'AND',
                [
                    new EqualsFilter('email', $userEmail),
                    new NotFilter('AND', [
                        new EqualsFilter('id', $userId),
                    ]),
                ]
            )
        );

        return $this->userRepo->searchIds($criteria, $context)->getTotal() === 0;
    }

    /**
     * @throws InconsistentCriteriaIdsException
     */
    public function checkUsernameUnique(string $userUsername, string $userId, Context $context): bool
    {
        $criteria = new Criteria();

        $criteria->addFilter(
            new MultiFilter(
                'AND',
                [
                    new EqualsFilter('username', $userUsername),
                    new NotFilter('AND', [
                        new EqualsFilter('id', $userId),
                    ]),
                ]
            )
        );

        return $this->userRepo->searchIds($criteria, $context)->getTotal() === 0;
    }
}
