<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.6.0 - will be removed, use MediaException::cannotOpenSourceStreamToRead instead
 */

class StreamNotReadableException extends SnapAdminHttpException
{
    public function __construct(string $path)
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use MediaException::cannotOpenSourceStreamToRead instead')
        );

        parent::__construct(
            'Could not read stream at following path: "{{ path }}"',
            ['path' => $path]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0', 'use MediaException::cannotOpenSourceStreamToRead instead')
        );

        return 'CONTENT__MEDIA_STREAM_NOT_READABLE';
    }

    public function getStatusCode(): int
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0', 'use MediaException::cannotOpenSourceStreamToRead instead')
        );

        return Response::HTTP_NOT_FOUND;
    }
}
