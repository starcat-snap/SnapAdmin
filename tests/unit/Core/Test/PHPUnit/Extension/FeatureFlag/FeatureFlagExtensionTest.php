<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Core\Test\PHPUnit\Extension\FeatureFlag;

use PHPUnit\Framework\Attributes\AfterClass;
use PHPUnit\Framework\Attributes\BeforeClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Test\Annotation\DisabledFeatures;
use SnapAdmin\Core\Test\PHPUnit\Extension\FeatureFlag\FeatureFlagExtension;

/**
 * @internal
 *
 * @phpstan-import-type FeatureFlagConfig from Feature
 */
#[CoversClass(FeatureFlagExtension::class)]
class FeatureFlagExtensionTest extends TestCase
{
    /**
     * @var array<string, mixed>
     */
    private static array $serverVars = [];

    #[BeforeClass]
    public static function registerTestFeature(): void
    {
        self::$serverVars = $_SERVER;

        Feature::registerFeature('foobar');
        $_SERVER['FOOBAR'] = true;

        static::assertTrue(Feature::isActive('foobar'));
    }

    #[AfterClass]
    public static function restoreServerVars(): void
    {
        $_SERVER = self::$serverVars;
    }

    public function testFeatureIsSet(): void
    {
        static::assertTrue(Feature::isActive('foobar'));
    }

    #[DisabledFeatures(['foobar'])]
    public function testFeatureIsDisabled(): void
    {
        static::assertFalse(Feature::isActive('foobar'));
    }
}
