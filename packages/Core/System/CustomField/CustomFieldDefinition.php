<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomField;

use SnapAdmin\Core\Content\Product\Aggregate\ProductSearchConfigField\ProductSearchConfigFieldDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetDefinition;

#[Package('system-settings')]
class CustomFieldDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'custom_field';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return CustomFieldCollection::class;
    }

    public function getEntityClass(): string
    {
        return CustomFieldEntity::class;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    public function getDefaults(): array
    {
        return [
            'allowCustomerWrites' => false,
            'allowCartExpose' => false,
        ];
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('type', 'type'))->addFlags(new Required()),
            new JsonField('config', 'config', [], []),
            new BoolField('active', 'active'),
            new FkField('set_id', 'customFieldSetId', CustomFieldSetDefinition::class),
            new BoolField('allow_customer_write', 'allowCustomerWrite'),
            new BoolField('allow_cart_expose', 'allowCartExpose'),
            new ManyToOneAssociationField('customFieldSet', 'set_id', CustomFieldSetDefinition::class, 'id', false),
            (new OneToManyAssociationField('productSearchConfigFields', ProductSearchConfigFieldDefinition::class, 'custom_field_id', 'id'))->addFlags(new CascadeDelete()),
        ]);
    }
}
