<?php declare(strict_types=1);

namespace SnapAdmin\Tests\Unit\Core\DevOps\StaticAnalyse\PHPStan\Rules\Test;

use PHPStan\Reflection\ClassReflection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\DevOps\StaticAnalyze\PHPStan\Rules\Tests\TestRuleHelper;

/**
 * @internal
 */
#[CoversClass(TestRuleHelper::class)]
class TestRuleHelperTest extends TestCase
{
    #[DataProvider('classProvider')]
    public function testIsTestClass(string $className, bool $extendsTestCase, bool $isTestClass, bool $isUnitTestClass): void
    {
        $classReflection = $this->createMock(ClassReflection::class);
        $classReflection
            ->method('getName')
            ->willReturn($className);

        if ($extendsTestCase) {
            $parentClass = $this->createMock(ClassReflection::class);
            $parentClass
                ->method('getName')
                ->willReturn(TestCase::class);

            $classReflection
                ->method('getParentClass')
                ->willReturn($parentClass);
        }

        static::assertEquals($isTestClass, TestRuleHelper::isTestClass($classReflection));
        static::assertEquals($isUnitTestClass, TestRuleHelper::isUnitTestClass($classReflection));
    }

    public static function classProvider(): \Generator
    {
        yield [
            'className' => 'SnapAdmin\Some\NonTestClass',
            'extendsTestCase' => false,
            'isTestClass' => false,
            'isUnitTestClass' => false,
        ];

        yield [
            'className' => 'SnapAdmin\Commercial\Tests\SomeTestClass',
            'extendsTestCase' => true,
            'isTestClass' => true,
            'isUnitTestClass' => false,
        ];

        yield [
            'className' => 'SnapAdmin\Tests\SomeTestClass',
            'extendsTestCase' => true,
            'isTestClass' => true,
            'isUnitTestClass' => false,
        ];

        yield [
            'className' => 'SnapAdmin\Tests\Unit\SomeTestClass',
            'extendsTestCase' => true,
            'isTestClass' => true,
            'isUnitTestClass' => true,
        ];

        yield [
            'className' => 'SnapAdmin\Tests\Integration\SomeTestClass',
            'extendsTestCase' => true,
            'isTestClass' => true,
            'isUnitTestClass' => false,
        ];

        yield [
            'className' => 'SnapAdmin\Tests\SomeNonTestClass',
            'extendsTestCase' => false,
            'isTestClass' => false,
            'isUnitTestClass' => false,
        ];
    }
}
