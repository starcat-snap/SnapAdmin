<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<TaxEntity>
 */
#[Package('checkout')]
class TaxCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'tax_collection';
    }

    protected function getExpectedClass(): string
    {
        return TaxEntity::class;
    }
}
