<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateTranslation;

use SnapAdmin\Core\Content\MailTemplate\MailTemplateDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailTemplateTranslationDefinition extends EntityTranslationDefinition
{
    final public const ENTITY_NAME = 'mail_template_translation';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return MailTemplateTranslationEntity::class;
    }

    public function getCollectionClass(): string
    {
        return MailTemplateTranslationCollection::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): string
    {
        return MailTemplateDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new StringField('sender_name', 'senderName'))->addFlags(new ApiAware()),
            (new LongTextField('description', 'description'))->addFlags(new ApiAware()),
            (new StringField('subject', 'subject'))->addFlags(new Required(), new AllowHtml(false)),
            (new LongTextField('content_html', 'contentHtml'))->addFlags(new Required(), new AllowHtml(false)),
            (new LongTextField('content_plain', 'contentPlain'))->addFlags(new Required(), new AllowHtml(false)),
            (new CustomFields())->addFlags(new ApiAware()),
        ]);
    }
}
