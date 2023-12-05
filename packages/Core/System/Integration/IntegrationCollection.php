<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Integration;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<IntegrationEntity>
 */
#[Package('core')]
class IntegrationCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'integration_collection';
    }

    protected function getExpectedClass(): string
    {
        return IntegrationEntity::class;
    }
}
