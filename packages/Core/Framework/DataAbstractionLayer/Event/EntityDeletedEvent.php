<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class EntityDeletedEvent extends EntityWrittenEvent
{
    public function __construct(
        string  $entityName,
        array   $writeResult,
        Context $context,
        array   $errors = []
    )
    {
        parent::__construct($entityName, $writeResult, $context, $errors);

        $this->name = $entityName . '.deleted';
    }
}
