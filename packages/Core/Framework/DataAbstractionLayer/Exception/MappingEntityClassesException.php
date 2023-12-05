<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class MappingEntityClassesException extends SnapAdminHttpException
{
    public function __construct()
    {
        parent::__construct('Mapping definition neither have entities nor collection.');
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__MAPPING_ENTITY_DEFINITION_CLASSES';
    }
}
