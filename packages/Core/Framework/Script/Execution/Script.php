<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Script\Execution;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Struct;

/**
 * @internal only for use by the app-system
 */
#[Package('core')]
class Script extends Struct
{
    public function __construct(
        protected string $name,
        protected string $script,
        protected \DateTimeInterface $lastModified,
        protected array $twigOptions = [],
        protected array $includes = [],
        private readonly bool $active = true
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getScript(): string
    {
        return $this->script;
    }

    public function getTwigOptions(): array
    {
        return $this->twigOptions;
    }

    public function getLastModified(): \DateTimeInterface
    {
        return $this->lastModified;
    }

    /**
     * @return Script[]
     */
    public function getIncludes(): array
    {
        return $this->includes;
    }

    public function isAppScript(): bool
    {
        return $this->scriptAppInformation !== null;
    }

    public function getScriptAppInformation(): ?ScriptAppInformation
    {
        return $this->scriptAppInformation;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
