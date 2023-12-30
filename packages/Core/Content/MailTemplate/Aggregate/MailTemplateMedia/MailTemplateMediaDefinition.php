<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateMedia;

use SnapAdmin\Core\Content\MailTemplate\MailTemplateDefinition;
use SnapAdmin\Core\Content\Media\MediaDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\MappingEntityDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Language\LanguageDefinition;

#[Package('services-settings')]
class MailTemplateMediaDefinition extends MappingEntityDefinition
{
    final public const ENTITY_NAME = 'mail_template_media';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return MailTemplateMediaCollection::class;
    }

    public function getEntityClass(): string
    {
        return MailTemplateMediaEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new FkField('mail_template_id', 'mailTemplateId', MailTemplateDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('language_id', 'languageId', LanguageDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('media_id', 'mediaId', MediaDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new IntField('position', 'position'))->addFlags(new ApiAware()),
            new ManyToOneAssociationField('mailTemplate', 'mail_template_id', MailTemplateDefinition::class, 'id', false),
            (new ManyToOneAssociationField('media', 'media_id', MediaDefinition::class, 'id', false))->addFlags(new ApiAware()),
        ]);
    }
}
