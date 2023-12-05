<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Field;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomEntity\Xml\Field\Traits\RequiredTrait;

/**
 * @internal
 */
#[Package('core')]
class ManyToOneField extends AssociationField
{
    use RequiredTrait;

    protected string $type = 'many-to-one';
}
