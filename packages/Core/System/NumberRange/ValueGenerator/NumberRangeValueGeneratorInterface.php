<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\ValueGenerator;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
interface NumberRangeValueGeneratorInterface
{
    /**
     * generates a new Value while taking Care of States, Events and Connectors
     */
    public function getValue(string $type, Context $context, bool $preview = false): string;

    /**
     * generates a preview for a given pattern and start
     */
    public function previewPattern(string $definition, ?string $pattern, int $start): string;
}
