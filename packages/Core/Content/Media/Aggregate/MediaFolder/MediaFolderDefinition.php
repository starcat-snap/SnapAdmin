<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaFolder;

use SnapAdmin\Core\Content\Media\Aggregate\MediaDefaultFolder\MediaDefaultFolderDefinition;
use SnapAdmin\Core\Content\Media\Aggregate\MediaFolderConfiguration\MediaFolderConfigurationDefinition;
use SnapAdmin\Core\Content\Media\MediaDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ChildCountField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SetNullOnDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TreePathField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;


class MediaFolderDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'media_folder';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return MediaFolderCollection::class;
    }

    public function getEntityClass(): string
    {
        return MediaFolderEntity::class;
    }

    public function getDefaults(): array
    {
        return ['useParentConfiguration' => true];
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            new BoolField('use_parent_configuration', 'useParentConfiguration'),
            (new FkField('media_folder_configuration_id', 'configurationId', MediaFolderConfigurationDefinition::class))->addFlags(new Required()),
            new FkField('default_folder_id', 'defaultFolderId', MediaDefaultFolderDefinition::class),
            new ParentFkField(self::class),
            new ParentAssociationField(self::class, 'id'),
            new ChildrenAssociationField(self::class),
            new ChildCountField(),
            new TreePathField('path', 'path'),
            (new OneToManyAssociationField('media', MediaDefinition::class, 'media_folder_id'))->addFlags(new SetNullOnDelete()),
            new OneToOneAssociationField('defaultFolder', 'default_folder_id', 'id', MediaDefaultFolderDefinition::class, false),
            new ManyToOneAssociationField('configuration', 'media_folder_configuration_id', MediaFolderConfigurationDefinition::class, 'id', false),
            (new StringField('name', 'name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING), new Required()),
            new CustomFields(),
        ]);
    }
}
