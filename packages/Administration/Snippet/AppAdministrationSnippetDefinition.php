<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Snippet;

use SnapAdmin\Core\Framework\App\AppDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\AllowEmptyString;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Locale\LocaleDefinition;

#[Package('administration')]
class AppAdministrationSnippetDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'app_administration_snippet';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return AppAdministrationSnippetCollection::class;
    }

    public function getEntityClass(): string
    {
        return AppAdministrationSnippetEntity::class;
    }

    public function since(): ?string
    {
        return '6.4.15.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new LongTextField('value', 'value'))->addFlags(new ApiAware(), new Required(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING), new AllowEmptyString()),

            (new FkField('app_id', 'appId', AppDefinition::class))->addFlags(new ApiAware(), new Required()),
            (new FkField('locale_id', 'localeId', LocaleDefinition::class))->addFlags(new ApiAware(), new Required()),
        ]);
    }
}
