<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class PrimaryKey extends Flag
{
    public function parse(): \Generator
    {
        yield 'primary_key' => true;
    }
}
