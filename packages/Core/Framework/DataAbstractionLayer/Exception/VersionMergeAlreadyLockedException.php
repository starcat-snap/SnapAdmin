<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - will be removed, use DataAbstractionLayerException::versionMergeAlreadyLocked instead
 */
#[Package('core')]
class VersionMergeAlreadyLockedException extends DataAbstractionLayerException
{
    public function __construct(string $versionId)
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use DataAbstractionLayerException::versionMergeAlreadyLocked instead')
        );

        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::VERSION_MERGE_ALREADY_LOCKED,
            'Merging of version {{ versionId }} is locked, as the merge is already running by another process.',
            ['versionId' => $versionId]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use DataAbstractionLayerException::versionMergeAlreadyLocked instead')
        );

        return 'FRAMEWORK__VERSION_MERGE_ALREADY_LOCKED';
    }
}
