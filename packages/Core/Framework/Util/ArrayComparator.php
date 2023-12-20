<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Util;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Util\Exception\ComparatorException;

#[Package('core')]
class ArrayComparator
{
    /**
     * @param array<string|int|bool|float> $a
     * @param array<string|int|bool|float> $b
     */
    public static function compare(array $a, array $b, string $operator): bool
    {
        return match ($operator) {
            '!=' => self::notEquals($a, $b),
            '=' => self::equals($a, $b),
            default => throw ComparatorException::operatorNotSupported($operator),
        };
    }

    /**
     * @param array<string|int|bool|float> $a
     * @param array<string|int|bool|float> $b
     */
    public static function equals(array $a, array $b): bool
    {
        return \count(array_intersect($a, $b)) > 0;
    }

    /**
     * @param array<string|int|bool|float> $a
     * @param array<string|int|bool|float> $b
     */
    public static function notEquals(array $a, array $b): bool
    {
        return \count(array_intersect($a, $b)) === 0;
    }
}
