<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooter;

use SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooterTranslation\MailHeaderFooterTranslationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslationsAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('services-settings')]
class MailHeaderFooterDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'mail_header_footer';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return MailHeaderFooterEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new BoolField('system_default', 'systemDefault'))->addFlags(new ApiAware()),

            // translatable fields
            (new TranslatedField('name'))->addFlags(new ApiAware()),
            (new TranslatedField('description'))->addFlags(new ApiAware()),
            (new TranslatedField('headerHtml'))->addFlags(new ApiAware()),
            (new TranslatedField('headerPlain'))->addFlags(new ApiAware()),
            (new TranslatedField('footerHtml'))->addFlags(new ApiAware()),
            (new TranslatedField('footerPlain'))->addFlags(new ApiAware()),

            (new TranslationsAssociationField(MailHeaderFooterTranslationDefinition::class, 'mail_header_footer_id'))->addFlags(new ApiAware(), new Required()),
        ]);
    }
}
