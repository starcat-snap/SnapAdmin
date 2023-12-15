<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Struct;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
trait CreateFromTrait
{
    #[\ReturnTypeWillChange]
    public static function createFrom(Struct $object): static
    {
        try {
            $self = (new \ReflectionClass(static::class))
                ->newInstanceWithoutConstructor();
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException($exception->getMessage());
        }

        foreach (get_object_vars($object) as $property => $value) {
            $self->$property = $value;
        }

        return $self;
    }
}
