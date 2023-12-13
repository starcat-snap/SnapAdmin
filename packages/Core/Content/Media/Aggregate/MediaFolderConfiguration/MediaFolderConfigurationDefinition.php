<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfiguration;

use SnapAdmin\Core\Content\Media\Aggregate\MediaFolder\MediaFolderDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfigurationMediaThumbnailSize\MediaFolderConfigurationMediaThumbnailSizeDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnailSize\MediaThumbnailSizeDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Computed;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;


class MediaFolderConfigurationDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'media_folder_configuration';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return MediaFolderConfigurationCollection::class;
    }

    public function getEntityClass(): string
    {
        return MediaFolderConfigurationEntity::class;
    }

    public function getDefaults(): array
    {
        return [
            'createThumbnails' => true,
            'keepAspectRatio' => true,
            'thumbnailQuality' => 80,
            'private' => false,
            'noAssociation' => false,
        ];
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new BoolField('create_thumbnails', 'createThumbnails'),
            new BoolField('keep_aspect_ratio', 'keepAspectRatio'),
            new IntField('thumbnail_quality', 'thumbnailQuality', 0, 100),
            new BoolField('private', 'private'),
            new BoolField('no_association', 'noAssociation'),
            new OneToManyAssociationField('mediaFolders', MediaFolderDefinition::class, 'media_folder_configuration_id', 'id'),
            new ManyToManyAssociationField('mediaThumbnailSizes', MediaThumbnailSizeDefinition::class, MediaFolderConfigurationMediaThumbnailSizeDefinition::class, 'media_folder_configuration_id', 'media_thumbnail_size_id'),
            (new BlobField('media_thumbnail_sizes_ro', 'mediaThumbnailSizesRo'))->removeFlag(ApiAware::class)->addFlags(new Computed()),
            new CustomFields(),
        ]);
    }
}
