<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\StringFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class StringField extends Field implements StorageAware
{
    public function __construct(
        private readonly string $storageName,
        string                  $propertyName,
        private readonly int    $maxLength = 255
    )
    {
        parent::__construct($propertyName);
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    protected function getSerializerClass(): string
    {
        return StringFieldSerializer::class;
    }
}
