<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Changelog;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @internal
 *
 * @extends Collection<ChangelogFile>
 */
#[Package('core')]
class ChangelogFileCollection extends Collection
{
    protected function getExpectedClass(): ?string
    {
        return ChangelogFile::class;
    }
}
