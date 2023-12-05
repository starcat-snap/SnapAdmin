<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @final
 */
#[Package('core')]
class PrefixFilter extends SingleFieldFilter
{
    protected readonly string $value;

    public function __construct(
        protected readonly string  $field,
        string|bool|float|int|null $value
    )
    {
        $this->value = (string)$value;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getFields(): array
    {
        return [$this->field];
    }
}
