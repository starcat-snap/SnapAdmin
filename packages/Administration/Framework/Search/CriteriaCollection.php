<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Framework\Search;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<Criteria>
 */
#[Package('administration')]
class CriteriaCollection extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return Criteria::class;
    }
}
