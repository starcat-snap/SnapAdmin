<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Changelog;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
enum ChangelogKeyword: string
{
    case ADDED = 'Added';
    case REMOVED = 'Removed';
    case CHANGED = 'Changed';
    case DEPRECATED = 'Deprecated';
}
