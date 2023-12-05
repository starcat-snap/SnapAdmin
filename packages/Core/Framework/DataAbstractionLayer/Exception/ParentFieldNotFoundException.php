<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class ParentFieldNotFoundException extends SnapAdminHttpException
{
    public function __construct(EntityDefinition $definition)
    {
        parent::__construct(
            'Can not find parent property \'parent\' field for definition {{ definition }',
            ['definition' => $definition->getEntityName()]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PARENT_FIELD_NOT_FOUND_EXCEPTION';
    }
}
