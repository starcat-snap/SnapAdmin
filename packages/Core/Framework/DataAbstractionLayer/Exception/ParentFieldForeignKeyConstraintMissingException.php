<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class ParentFieldForeignKeyConstraintMissingException extends SnapAdminHttpException
{
    public function __construct(
        EntityDefinition $definition,
        Field            $parentField
    )
    {
        parent::__construct(
            'Foreign key property {{ propertyName }} of parent association in definition {{ definition }} expected to be an FkField got %s',
            [
                'definition' => $definition->getEntityName(),
                'propertyName' => $parentField->getPropertyName(),
                'propertyClass' => $parentField::class,
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PARENT_FIELD_KEY_CONSTRAINT_MISSING';
    }
}
