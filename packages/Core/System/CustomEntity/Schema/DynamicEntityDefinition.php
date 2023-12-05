<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\CustomEntity\Schema;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Flag;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @internal The use of this class is reserved for the custom_entity feature
 *
 * @phpstan-import-type CustomEntityField from CustomEntitySchemaUpdater
 */
#[Package('core')]
class DynamicEntityDefinition extends EntityDefinition
{
    protected string $name;

    /**
     * @var list<CustomEntityField>
     */
    protected array $fieldDefinitions;

    /**
     * @var list<Flag>
     */
    protected array $flags;

    protected ContainerInterface $container;

    /**
     * @param list<CustomEntityField> $fields
     * @param list<Flag> $flags
     */
    public static function create(
        string             $name,
        array              $fields,
        array              $flags,
        ContainerInterface $container
    ): DynamicEntityDefinition
    {
        $self = new self();
        $self->name = $name;
        $self->fieldDefinitions = $fields;
        $self->container = $container;
        $self->flags = $flags;

        return $self;
    }

    public function getEntityName(): string
    {
        return $this->name;
    }

    /**
     * @return list<Flag>
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    protected function defineFields(): FieldCollection
    {
        $collection = DynamicFieldFactory::create($this->container, $this->getEntityName(), $this->fieldDefinitions);

        $collection->add(
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new Required(), new PrimaryKey()),
        );

        return $collection;
    }
}
