<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<Field>
 */
#[Package('core')]
class FieldCollection extends Collection
{
    public function compile(DefinitionInstanceRegistry $registry): CompiledFieldCollection
    {
        foreach ($this->elements as $field) {
            $field->compile($registry);
        }

        return new CompiledFieldCollection($registry, $this->elements);
    }

    public function getApiAlias(): string
    {
        return 'dal_field_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return Field::class;
    }
}
