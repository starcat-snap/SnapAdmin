<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Field\Traits;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
trait RequiredTrait
{
    protected bool $required = false;

    public function isRequired(): bool
    {
        return $this->required;
    }
}
