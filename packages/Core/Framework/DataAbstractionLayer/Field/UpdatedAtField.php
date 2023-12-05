<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\UpdatedAtFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class UpdatedAtField extends DateTimeField
{
    public function __construct()
    {
        parent::__construct('updated_at', 'updatedAt');
    }

    protected function getSerializerClass(): string
    {
        return UpdatedAtFieldSerializer::class;
    }
}
