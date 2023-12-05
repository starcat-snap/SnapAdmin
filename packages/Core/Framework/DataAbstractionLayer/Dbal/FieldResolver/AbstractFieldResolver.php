<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldResolver;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
abstract class AbstractFieldResolver
{
    abstract public function join(FieldResolverContext $context): string;
}
