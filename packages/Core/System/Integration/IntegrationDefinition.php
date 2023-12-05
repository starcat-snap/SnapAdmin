<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Integration;

use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\App\AppDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Integration\Aggregate\IntegrationRole\IntegrationRoleDefinition;

#[Package('system-settings')]
class IntegrationDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'integration';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return IntegrationCollection::class;
    }

    public function getEntityClass(): string
    {
        return IntegrationEntity::class;
    }

    public function getDefaults(): array
    {
        return [
            'admin' => false,
        ];
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('label', 'label'))->addFlags(new Required()),
            (new StringField('access_key', 'accessKey'))->addFlags(new Required()),
            (new PasswordField('secret_access_key', 'secretAccessKey'))->addFlags(new Required()),
            new DateTimeField('last_usage_at', 'lastUsageAt'),
            new BoolField('admin', 'admin'),
            new CustomFields(),
            new DateTimeField('deleted_at', 'deletedAt'),

            (new OneToOneAssociationField('app', 'id', 'integration_id', AppDefinition::class, false))->addFlags(new RestrictDelete()),
            new ManyToManyAssociationField('aclRoles', AclRoleDefinition::class, IntegrationRoleDefinition::class, 'integration_id', 'acl_role_id'),
        ]);
    }
}
