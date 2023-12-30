<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentType;

use SnapAdmin\Core\Content\Document\Aggregate\DocumentBaseConfig\DocumentBaseConfigDefinition;
use SnapAdmin\Core\Content\Document\Aggregate\DocumentTypeTranslation\DocumentTypeTranslationDefinition;
use SnapAdmin\Core\Content\Document\DocumentDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
class DocumentTypeDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'document_type';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return DocumentTypeCollection::class;
    }

    public function getEntityClass(): string
    {
        return DocumentTypeEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),

            (new TranslatedField('name'))->addFlags(new ApiAware(), new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('technical_name', 'technicalName'))->addFlags(new ApiAware(), new Required()),
            (new CreatedAtField())->addFlags(new ApiAware()),
            (new UpdatedAtField())->addFlags(new ApiAware()),
            (new TranslatedField('customFields'))->addFlags(new ApiAware()),

            (new TranslationsAssociationField(DocumentTypeTranslationDefinition::class, 'document_type_id'))->addFlags(new ApiAware(), new Required()),
            new OneToManyAssociationField('documents', DocumentDefinition::class, 'document_type_id'),
            (new OneToManyAssociationField('documentBaseConfigs', DocumentBaseConfigDefinition::class, 'document_type_id'))->addFlags(new CascadeDelete()),
        ]);
    }
}
