<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Term;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface TokenizerInterface
{
    /**
     * @return list<string>
     */
    public function tokenize(string $string): array;
}
