<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Storage;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
abstract class AbstractKeyValueStorage
{
    abstract public function has(string $key): bool;

    abstract public function get(string $key, mixed $default = null): mixed;

    abstract public function set(string $key, mixed $value): void;

    abstract public function remove(string $key): void;
}
