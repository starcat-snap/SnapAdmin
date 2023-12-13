<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfigurationMediaThumbnailSize;

use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfiguration\MediaFolderConfigurationDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaThumbnailSize\MediaThumbnailSizeDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;


class MediaFolderConfigurationMediaThumbnailSizeDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'media_folder_configuration_media_thumbnail_size';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new FkField('media_folder_configuration_id', 'mediaFolderConfigurationId', MediaFolderConfigurationDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('media_thumbnail_size_id', 'mediaThumbnailSizeId', MediaThumbnailSizeDefinition::class))->addFlags(new PrimaryKey(), new Required()),
            new ManyToOneAssociationField('mediaFolderConfiguration', 'media_folder_configuration_id', MediaFolderConfigurationDefinition::class, 'id', false),
            new ManyToOneAssociationField('mediaThumbnailSize', 'media_thumbnail_size_id', MediaThumbnailSizeDefinition::class, 'id', false),
        ]);
    }
}
