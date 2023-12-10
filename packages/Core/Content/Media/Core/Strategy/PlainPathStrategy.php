<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Core\Strategy;

use SnapAdmin\Core\Content\Media\Core\Application\AbstractMediaPathStrategy;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal Concrete implementation is not allowed to be decorated or extended. The implementation details can change
 */
#[Package('content')]
class PlainPathStrategy extends AbstractMediaPathStrategy
{
    public function name(): string
    {
        return 'plain';
    }
}
