<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\File;

use SnapAdmin\Core\Framework\Log\Package;


interface FileUrlValidatorInterface
{
    public function isValid(string $source): bool;
}
