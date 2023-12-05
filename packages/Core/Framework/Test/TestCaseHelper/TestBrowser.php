<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseHelper;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

/**
 * @internal
 */
class TestBrowser extends KernelBrowser
{
    public function setServerParameter(string $key, mixed $value): void
    {
        $this->server[$key] = $value;
    }
}
