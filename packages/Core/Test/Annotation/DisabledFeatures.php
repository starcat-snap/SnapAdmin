<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\Annotation;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
#[Package('core')]
final class DisabledFeatures
{
    /**
     * @param array<string> $features
     */
    public function __construct(public array $features = [])
    {
    }
}
