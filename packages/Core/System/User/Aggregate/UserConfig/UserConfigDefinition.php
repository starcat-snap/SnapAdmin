<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Aggregate\UserConfig;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\UserDefinition;

#[Package('system-settings')]
class UserConfigDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'user_config';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return UserConfigEntity::class;
    }

    public function getCollectionClass(): string
    {
        return UserConfigCollection::class;
    }

    public function since(): ?string
    {
        return '6.3.5.0';
    }

    protected function getParentDefinitionClass(): ?string
    {
        return UserDefinition::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new FkField('user_id', 'userId', UserDefinition::class))->addFlags(new Required()),
            (new StringField('key', 'key'))->addFlags(new Required()),
            new JsonField('value', 'value'),

            new ManyToOneAssociationField('user', 'user_id', UserDefinition::class, 'id', false),
        ]);
    }
}
