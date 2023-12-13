<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class PrimaryKeyNotProvidedException extends SnapAdminHttpException
{
    public function __construct(
        EntityDefinition $definition,
        Field $field
    ) {
        parent::__construct(
            'Expected primary key field {{ propertyName }} for definition {{ definition }} not provided',
            ['definition' => $definition->getEntityName(), 'propertyName' => $field->getPropertyName()]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PRIMARY_KEY_NOT_PROVIDED';
    }
}
