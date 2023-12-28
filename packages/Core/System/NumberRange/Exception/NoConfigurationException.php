<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class NoConfigurationException extends SnapAdminHttpException
{
    public function __construct(
        string $entityName,
        ?string $salesChannelId = null
    ) {
        parent::__construct(
            'No number range configuration found for entity "{{ entity }}".',
            ['entity' => $entityName, 'salesChannelId' => $salesChannelId]
        );
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__NO_NUMBER_RANGE_CONFIGURATION';
    }
}
