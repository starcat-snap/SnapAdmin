<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * The value is computed by indexer or external systems and
 * cannot be written using the DAL.
 */
#[Package('core')]
class Computed extends Flag
{
    public function parse(): \Generator
    {
        yield 'computed' => true;
    }
}
