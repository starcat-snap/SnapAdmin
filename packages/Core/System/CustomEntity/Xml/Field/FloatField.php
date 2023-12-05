<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Field;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\Xml\Field\Traits\RequiredTrait;
use SnapAdmin\Core\System\CustomEntity\Xml\Field\Traits\TranslatableTrait;

/**
 * @internal
 */
#[Package('core')]
class FloatField extends Field
{
    use RequiredTrait;
    use TranslatableTrait;

    protected string $type = 'float';
}
