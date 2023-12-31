<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class ObjectField extends JsonField
{
    public function __construct(
        string $storageName,
        string $propertyName
    ) {
        parent::__construct($storageName, $propertyName);
    }
}
