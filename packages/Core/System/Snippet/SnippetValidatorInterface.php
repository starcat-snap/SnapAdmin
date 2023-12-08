<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
interface SnippetValidatorInterface
{
    public function validate(): array;
}
