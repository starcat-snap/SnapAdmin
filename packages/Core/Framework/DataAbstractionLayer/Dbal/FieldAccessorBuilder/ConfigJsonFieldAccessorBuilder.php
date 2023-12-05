<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ConfigJsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class ConfigJsonFieldAccessorBuilder extends JsonFieldAccessorBuilder
{
    public function buildAccessor(string $root, Field $field, Context $context, string $accessor): ?string
    {
        if (!$field instanceof ConfigJsonField) {
            return null;
        }

        $jsonPath = preg_replace(
            '#^' . preg_quote($field->getPropertyName(), '#') . '#',
            '',
            $accessor
        );

        $accessor = $field->getPropertyName() . '.' . ConfigJsonField::STORAGE_KEY . $jsonPath;

        return parent::buildAccessor($root, $field, $context, $accessor);
    }
}
