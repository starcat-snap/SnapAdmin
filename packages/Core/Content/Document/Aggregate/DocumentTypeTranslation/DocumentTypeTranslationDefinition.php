<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentTypeTranslation;

use SnapAdmin\Core\Content\Document\Aggregate\DocumentType\DocumentTypeDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
class DocumentTypeTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'document_type_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return DocumentTypeTranslationEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return DocumentTypeDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
