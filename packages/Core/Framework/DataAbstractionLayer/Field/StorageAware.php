<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface StorageAware
{
    public function getStorageName(): string;
}
