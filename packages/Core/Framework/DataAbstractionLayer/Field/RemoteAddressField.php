<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\RemoteAddressFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class RemoteAddressField extends Field implements StorageAware
{
    public function __construct(
        private readonly string $storageName,
        string                  $propertyName
    )
    {
        parent::__construct($propertyName);
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    protected function getSerializerClass(): string
    {
        return RemoteAddressFieldSerializer::class;
    }
}
