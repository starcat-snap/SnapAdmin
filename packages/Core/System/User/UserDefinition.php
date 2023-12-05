<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User;

use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\Api\Acl\Role\AclUserRoleDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection\EntityProtectionCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection\WriteProtection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\SearchRanking;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TimeZoneField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Locale\LocaleDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserAccessKey\UserAccessKeyDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserConfig\UserConfigDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryDefinition;

#[Package('system-settings')]
class UserDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'user';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return UserCollection::class;
    }

    public function getEntityClass(): string
    {
        return UserEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    public function getDefaults(): array
    {
        return [
            'timeZone' => 'UTC',
        ];
    }

    protected function defineProtections(): EntityProtectionCollection
    {
        return new EntityProtectionCollection([new WriteProtection(Context::SYSTEM_SCOPE)]);
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('locale_id', 'localeId', LocaleDefinition::class))->addFlags(new Required()),
            (new StringField('username', 'username'))->addFlags(new Required(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new PasswordField('password', 'password', \PASSWORD_DEFAULT, [], PasswordField::FOR_ADMIN))->removeFlag(ApiAware::class)->addFlags(new Required()),
            (new StringField('name', 'name'))->addFlags(new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new StringField('phone', 'phone'))->addFlags( new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            (new StringField('title', 'title'))->addFlags(new SearchRanking(SearchRanking::MIDDLE_SEARCH_RANKING)),
            (new StringField('email', 'email'))->addFlags(new Required(), new SearchRanking(SearchRanking::HIGH_SEARCH_RANKING)),
            new BoolField('active', 'active'),
            new BoolField('admin', 'admin'),
            new DateTimeField('last_updated_password_at', 'lastUpdatedPasswordAt'),
            (new TimeZoneField('time_zone', 'timeZone'))->addFlags(new Required()),
            new CustomFields(),
            new ManyToOneAssociationField('locale', 'locale_id', LocaleDefinition::class, 'id', false),
            (new OneToManyAssociationField('accessKeys', UserAccessKeyDefinition::class, 'user_id', 'id'))->addFlags(new CascadeDelete()),
            (new OneToManyAssociationField('configs', UserConfigDefinition::class, 'user_id', 'id'))->addFlags(new CascadeDelete()),
            new ManyToManyAssociationField('aclRoles', AclRoleDefinition::class, AclUserRoleDefinition::class, 'user_id', 'acl_role_id'),
            new OneToOneAssociationField('recoveryUser', 'id', 'user_id', UserRecoveryDefinition::class, false),
            (new StringField('store_token', 'storeToken'))->removeFlag(ApiAware::class),
        ]);
    }
}
