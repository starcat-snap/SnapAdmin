<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<CurrencyEntity>
 */
#[Package('core')]
class CurrencyCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'currency_collection';
    }

    protected function getExpectedClass(): string
    {
        return CurrencyEntity::class;
    }
}
