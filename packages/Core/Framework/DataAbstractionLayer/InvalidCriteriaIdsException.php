<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class InvalidCriteriaIdsException extends DataAbstractionLayerException
{
}
