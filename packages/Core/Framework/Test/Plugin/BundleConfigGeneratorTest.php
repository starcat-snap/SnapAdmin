<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\Plugin;

use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Plugin\BundleConfigGenerator;
use SnapAdmin\Core\Framework\Plugin\BundleConfigGeneratorInterface;
use SnapAdmin\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;

/**
 * @internal
 */
class BundleConfigGeneratorTest extends TestCase
{
    use IntegrationTestBehaviour;

    private BundleConfigGeneratorInterface $configGenerator;

    protected function setUp(): void
    {
        $this->configGenerator = $this->getContainer()->get(BundleConfigGenerator::class);
    }
}
