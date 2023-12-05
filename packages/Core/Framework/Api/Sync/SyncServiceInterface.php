<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Sync;

use Doctrine\DBAL\ConnectionException;
use SnapAdmin\Core\Framework\Api\Exception\InvalidSyncOperationException;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
interface SyncServiceInterface
{
    /**
     * @param list<SyncOperation> $operations
     *
     * @throws ConnectionException
     * @throws InvalidSyncOperationException
     */
    public function sync(array $operations, Context $context, SyncBehavior $behavior): SyncResult;
}
