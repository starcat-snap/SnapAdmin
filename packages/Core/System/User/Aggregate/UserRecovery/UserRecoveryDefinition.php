<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Aggregate\UserRecovery;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\UserDefinition;

#[Package('system-settings')]
class UserRecoveryDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'user_recovery';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return UserRecoveryEntity::class;
    }

    public function getCollectionClass(): string
    {
        return UserRecoveryCollection::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function getParentDefinitionClass(): ?string
    {
        return UserDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('hash', 'hash'))->addFlags(new Required()),
            (new FkField('user_id', 'userId', UserDefinition::class))->addFlags(new Required()),
            (new CreatedAtField())->addFlags(new Required()),

            new OneToOneAssociationField('user', 'user_id', 'id', UserDefinition::class, false),
        ]);
    }
}
