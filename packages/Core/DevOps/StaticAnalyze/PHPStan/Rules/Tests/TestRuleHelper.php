<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\StaticAnalyze\PHPStan\Rules\Tests;

use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class TestRuleHelper
{
    public static function isTestClass(ClassReflection $class): bool
    {
        if ($class->getParentClass() !== null && $class->getParentClass()->getName() === TestCase::class) {
            return true;
        }

        return false;
    }

    public static function isUnitTestClass(ClassReflection $class): bool
    {
        if (!static::isTestClass($class)) {
            return false;
        }

        $unitTestNamespaces = [
            'SnapAdmin\\Tests\\Unit\\',
            'SnapAdmin\\Tests\\Migration\\',

            'SnapAdmin\\Commercial\\Tests\\Unit\\',
            'SnapAdmin\\Commercial\\Migration\\Test\\',

            'Swag\\SaasRufus\\Test\\Migration\\',
            'Swag\\SaasRufus\\Tests\\Unit\\',
        ];

        foreach ($unitTestNamespaces as $unitTestNamespace) {
            if (\str_contains($class->getName(), $unitTestNamespace)) {
                return true;
            }
        }

        return false;
    }
}
