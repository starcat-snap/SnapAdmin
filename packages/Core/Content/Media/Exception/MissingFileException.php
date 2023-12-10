<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Exception;

use SnapAdmin\Core\Content\Media\MediaException;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - will be removed, use MediaException::missingFile instead
 */

class MissingFileException extends MediaException
{
    public function __construct(string $mediaId)
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use MediaException::missingFile instead')
        );

        parent::__construct(
            Response::HTTP_NOT_FOUND,
            self::MEDIA_MISSING_FILE,
            'Could not find file for media with id: "{{ mediaId }}"',
            ['mediaId' => $mediaId]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0', 'use MediaException::missingFile instead')
        );

        return 'CONTENT__MEDIA_MISSING_FILE';
    }

    public function getStatusCode(): int
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0', 'use MediaException::missingFile instead')
        );

        return Response::HTTP_NOT_FOUND;
    }
}
