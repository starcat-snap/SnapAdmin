<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnail;

use SnapAdmin\Core\Content\Media\MediaDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\WriteProtected;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;

class MediaThumbnailDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'media_thumbnail';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return MediaThumbnailCollection::class;
    }

    public function getEntityClass(): string
    {
        return MediaThumbnailEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): ?string
    {
        return MediaDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),

            (new FkField('media_id', 'mediaId', MediaDefinition::class))->addFlags(new ApiAware(), new Required()),

            (new IntField('width', 'width'))->addFlags(new ApiAware(), new Required(), new WriteProtected(Context::SYSTEM_SCOPE)),
            (new IntField('height', 'height'))->addFlags(new ApiAware(), new Required(), new WriteProtected(Context::SYSTEM_SCOPE)),
            (new StringField('url', 'url'))->addFlags(new ApiAware(), new Runtime(['path', 'updatedAt'])),
            (new StringField('path', 'path'))->addFlags(new ApiAware()),
            new ManyToOneAssociationField('media', 'media_id', MediaDefinition::class, 'id', false),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
