<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Version;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<VersionEntity>
 */
#[Package('core')]
class VersionCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'dal_version_collection';
    }

    protected function getExpectedClass(): string
    {
        return VersionEntity::class;
    }
}
