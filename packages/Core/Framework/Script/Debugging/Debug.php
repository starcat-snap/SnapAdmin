<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Debugging;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class Debug
{
    protected array $dumps = [];

    public function dump(mixed $value, ?string $key = null): void
    {
        if ($key !== null) {
            $this->dumps[$key] = $value;
        } else {
            $this->dumps[] = $value;
        }
    }

    public function all(): array
    {
        return $this->dumps;
    }
}
