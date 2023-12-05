<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface SnapAdminException extends \Throwable
{
    public function getErrorCode(): string;

    /**
     * @return array<string|int, mixed|null>
     */
    public function getParameters(): array;
}
