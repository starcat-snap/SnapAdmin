<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Aggregate\FlowTemplate;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Flow\DataAbstractionLayer\Field\FlowTemplateConfigField;

#[Package('services-settings')]
class FlowTemplateDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = 'flow_template';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return FlowTemplateCollection::class;
    }

    public function getEntityClass(): string
    {
        return FlowTemplateEntity::class;
    }

    public function since(): ?string
    {
        return '6.4.18.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('name', 'name', 255))->addFlags(new Required()),
            new FlowTemplateConfigField('config', 'config'),
        ]);
    }
}
