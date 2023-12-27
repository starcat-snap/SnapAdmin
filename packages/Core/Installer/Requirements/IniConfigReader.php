<?php declare(strict_types=1);

namespace SnapAdmin\Core\Installer\Requirements;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * Extracted to be able to mock all ini values
 *
 * @internal
 */
#[Package('core')]
class IniConfigReader
{
    public function get(string $key): string
    {
        return (string) \ini_get($key);
    }
}
