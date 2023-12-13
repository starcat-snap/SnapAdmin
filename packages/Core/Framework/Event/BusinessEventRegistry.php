<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
class BusinessEventRegistry
{
    /**
     * @var array<string>
     */
    private array $classes = [
    ];

    public function addClasses(array $classes): void
    {
        $this->classes = array_unique(array_merge($this->classes, $classes));
    }

    public function getClasses(): array
    {
        return $this->classes;
    }
}
