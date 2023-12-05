<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\EntityDefinitionQueryHelper;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\Exception\FieldNotStorageAwareException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StorageAware;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class DefaultFieldAccessorBuilder implements FieldAccessorBuilderInterface
{
    public function buildAccessor(string $root, Field $field, Context $context, string $accessor): string
    {
        if (!$field instanceof StorageAware) {
            throw new FieldNotStorageAwareException($root . '.' . $field->getPropertyName());
        }

        return EntityDefinitionQueryHelper::escape($root) . '.' . EntityDefinitionQueryHelper::escape($field->getStorageName());
    }
}
