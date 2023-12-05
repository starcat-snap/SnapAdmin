<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<SnapAdminHttpException>
 */
#[Package('core')]
class ExceptionCollection extends Collection
{
    public function getApiAlias(): string
    {
        return 'plugin_exception_collection';
    }

    protected function getExpectedClass(): ?string
    {
        return SnapAdminHttpException::class;
    }
}
