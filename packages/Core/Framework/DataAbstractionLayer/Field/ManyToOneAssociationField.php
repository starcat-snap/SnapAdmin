<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldResolver\ManyToOneAssociationFieldResolver;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\ManyToOneAssociationFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class ManyToOneAssociationField extends AssociationField
{
    final public const PRIORITY = 80;

    /**
     * @var string
     */
    protected $storageName;

    public function __construct(
        string $propertyName,
        string $storageName,
        string $referenceClass,
        string $referenceField = 'id',
        bool   $autoload = false
    )
    {
        parent::__construct($propertyName);

        $this->referenceClass = $referenceClass;
        $this->storageName = $storageName;
        $this->referenceField = $referenceField;
        $this->autoload = $autoload;
    }

    public function getStorageName(): string
    {
        return $this->storageName;
    }

    public function getExtractPriority(): int
    {
        return self::PRIORITY;
    }

    protected function getSerializerClass(): string
    {
        return ManyToOneAssociationFieldSerializer::class;
    }

    protected function getResolverClass(): ?string
    {
        return ManyToOneAssociationFieldResolver::class;
    }
}
