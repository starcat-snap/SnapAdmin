<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<EntityProtection>
 */
#[Package('core')]
class EntityProtectionCollection extends Collection
{
    /**
     * @param EntityProtection $element
     */
    public function add($element): void
    {
        $this->set($element::class, $element);
    }

    /**
     * @param string|int       $key
     * @param EntityProtection $element
     */
    public function set($key, $element): void
    {
        parent::set($element::class, $element);
    }

    public function getApiAlias(): string
    {
        return 'dal_protection_collection';
    }
}
