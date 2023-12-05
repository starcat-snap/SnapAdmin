<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\CreatedAtFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class CreatedAtField extends DateTimeField
{
    public function __construct()
    {
        parent::__construct('created_at', 'createdAt');
        $this->addFlags(new Required());
    }

    protected function getSerializerClass(): string
    {
        return CreatedAtFieldSerializer::class;
    }
}
