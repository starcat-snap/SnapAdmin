<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
interface CriteriaPartInterface
{
    /**
     * @return list<string>
     */
    public function getFields(): array;
}
