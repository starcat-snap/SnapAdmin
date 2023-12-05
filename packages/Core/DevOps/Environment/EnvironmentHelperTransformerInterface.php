<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\Environment;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface EnvironmentHelperTransformerInterface
{
    public static function transform(EnvironmentHelperTransformerData $data): void;
}
