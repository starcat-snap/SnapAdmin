<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Acl\Role;

use SnapAdmin\Core\Framework\App\AppDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection\EntityProtectionCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityProtection\WriteProtection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ListField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Integration\Aggregate\IntegrationRole\IntegrationRoleDefinition;
use SnapAdmin\Core\System\Integration\IntegrationDefinition;
use SnapAdmin\Core\System\User\UserDefinition;

#[Package('core')]
class AclRoleDefinition extends EntityDefinition
{
    final public const PRIVILEGE_READ = 'read';
    final public const PRIVILEGE_CREATE = 'create';
    final public const PRIVILEGE_UPDATE = 'update';
    final public const PRIVILEGE_DELETE = 'delete';

    final public const PRIVILEGE_DEPENDENCE = [
        AclRoleDefinition::PRIVILEGE_READ => [],
        AclRoleDefinition::PRIVILEGE_CREATE => [AclRoleDefinition::PRIVILEGE_READ],
        AclRoleDefinition::PRIVILEGE_UPDATE => [AclRoleDefinition::PRIVILEGE_READ],
        AclRoleDefinition::PRIVILEGE_DELETE => [AclRoleDefinition::PRIVILEGE_READ],
    ];

    final public const ENTITY_NAME = 'acl_role';
    final public const ALL_ROLE_KEY = 'all';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return AclRoleCollection::class;
    }

    public function getEntityClass(): string
    {
        return AclRoleEntity::class;
    }

    public function getDefaults(): array
    {
        return ['privileges' => []];
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineProtections(): EntityProtectionCollection
    {
        return new EntityProtectionCollection([
            new WriteProtection(Context::SYSTEM_SCOPE),
        ]);
    }

    protected function defineFields(): FieldCollection
    {
        $collection = new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            new LongTextField('description', 'description'),
            (new ListField('privileges', 'privileges'))->addFlags(new Required()),
            new DateTimeField('deleted_at', 'deletedAt'),
            new ManyToManyAssociationField('users', UserDefinition::class, AclUserRoleDefinition::class, 'acl_role_id', 'user_id'),
            (new OneToOneAssociationField('app', 'id', 'acl_role_id', AppDefinition::class, false))->addFlags(new RestrictDelete()),
            new ManyToManyAssociationField('integrations', IntegrationDefinition::class, IntegrationRoleDefinition::class, 'acl_role_id', 'integration_id'),
        ]);

        return $collection;
    }
}
