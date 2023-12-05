<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Cache\Message;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\MessageQueue\AsyncMessageInterface;

#[Package('core')]
class CleanupOldCacheFolders implements AsyncMessageInterface
{
}
