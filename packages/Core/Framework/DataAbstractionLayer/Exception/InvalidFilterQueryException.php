<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class InvalidFilterQueryException extends DataAbstractionLayerException
{
}
