<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class InvalidParentAssociationException extends SnapAdminHttpException
{
    public function __construct(
        EntityDefinition $definition,
        Field $parentField
    ) {
        parent::__construct(
            'Parent property for {{ definition }} expected to be an ManyToOneAssociationField got {{ fieldDefinition }}',
            ['definition' => $definition->getEntityName(), 'fieldDefinition' => $parentField::class]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_PARENT_ASSOCIATION_EXCEPTION';
    }
}
