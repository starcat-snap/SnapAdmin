<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class InvalidSortQueryException extends DataAbstractionLayerException
{
    public function __construct(?string $message = null, array $parameters = [])
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            DataAbstractionLayerException::INVALID_SORT_QUERY,
            $message ?? 'Invalid sort query',
            $parameters
        );
    }
}
