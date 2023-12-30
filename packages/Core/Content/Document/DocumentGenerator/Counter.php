<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\DocumentGenerator;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
class Counter
{
    private int $counter = 0;

    public function getCounter(): int
    {
        return $this->counter;
    }

    public function increment(): void
    {
        $this->counter = $this->counter + 1;
    }
}
