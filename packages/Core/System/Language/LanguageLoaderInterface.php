<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @phpstan-type LanguageData array<string, array{id: string, code: string, parentId: string}>
 */
#[Package('core')]
interface LanguageLoaderInterface
{
    /**
     * @return LanguageData
     */
    public function loadLanguages(): array;
}
