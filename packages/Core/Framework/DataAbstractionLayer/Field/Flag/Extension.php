<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * Defines that the data of this field is stored in an Entity::$extension and are not part of the struct itself.
 */
#[Package('core')]
class Extension extends Flag
{
    public function parse(): \Generator
    {
        yield 'extension' => true;
    }
}
