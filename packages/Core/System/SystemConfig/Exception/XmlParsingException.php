<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\SystemConfig\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @deprecated tag:v6.7.0 - will be removed, use domain specific xmlParsingExceptions instead
 */
#[Package('system-settings')]
class XmlParsingException extends SnapAdminHttpException
{
    public function __construct(
        string $xmlFile,
        string $message
    ) {
        Feature::triggerDeprecationOrThrow(
            'v6.7.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.7.0.0', 'domain specific xmlParsingExceptions')
        );

        parent::__construct(
            'Unable to parse file "{{ file }}". Message: {{ message }}',
            ['file' => $xmlFile, 'message' => $message]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow(
            'v6.7.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.7.0.0', 'domain specific xmlParsingExceptions')
        );

        return 'SYSTEM__XML_PARSE_ERROR';
    }

    public function getStatusCode(): int
    {
        Feature::triggerDeprecationOrThrow(
            'v6.7.0.0',
            Feature::deprecatedClassMessage(self::class, 'v6.7.0.0', 'domain specific xmlParsingExceptions')
        );

        return Response::HTTP_BAD_REQUEST;
    }
}
