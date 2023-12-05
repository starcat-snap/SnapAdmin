<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Xml\Field;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class LabelField extends Field
{
    protected string $type = 'label';
}
