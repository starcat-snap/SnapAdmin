<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidSalesChannelIdException extends SnapAdminHttpException
{
    public function __construct(string $channelId)
    {
        parent::__construct(
            'The provided channelId "{{ channelId }}" is invalid.',
            ['channelId' => $channelId]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INVALID_SALES_CHANNEL';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
