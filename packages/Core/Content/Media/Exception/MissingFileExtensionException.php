<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @deprecated tag:v6.6.0 - will be removed, use MediaException::missingFileExtension instead
 */

class MissingFileExtensionException extends UploadException
{
    public function __construct()
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.6.0.0', 'use MediaException::missingFileExtension instead')
        );

        parent::__construct(
            'No file extension provided. Please use the "extension" query parameter to specify the extension of the uploaded file.'
        );
    }
}
