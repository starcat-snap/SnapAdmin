<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Locale;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<LocaleEntity>
 */
#[Package('core')]
class LocaleCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'locale_collection';
    }

    protected function getExpectedClass(): string
    {
        return LocaleEntity::class;
    }
}
