<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet\Struct;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\Collection;

/**
 * @extends Collection<MissingSnippetStruct>
 */
#[Package('system-settings')]
class MissingSnippetCollection extends Collection
{
}
