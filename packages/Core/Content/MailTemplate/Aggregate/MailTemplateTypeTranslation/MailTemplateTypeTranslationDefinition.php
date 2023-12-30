<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTypeTranslation;

use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateType\MailTemplateTypeDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailTemplateTypeTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'mail_template_type_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return MailTemplateTypeTranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return MailTemplateTypeTranslationCollection::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return MailTemplateTypeDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
