<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Indexing;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Event\EntityWrittenContainerEvent;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
abstract class PostUpdateIndexer extends EntityIndexer
{
    final public function update(EntityWrittenContainerEvent $event): ?EntityIndexingMessage
    {
        return null;
    }
}
