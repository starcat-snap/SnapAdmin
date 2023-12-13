<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class InternalFieldAccessNotAllowedException extends \RuntimeException
{
    public function __construct(
        string $property,
        object $entity
    ) {
        parent::__construct(sprintf('Access to property "%s" not allowed on entity "%s".', $property, $entity::class));
    }
}
