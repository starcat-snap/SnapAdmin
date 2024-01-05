<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Tax\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('checkout')]
class TaxNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $taxId)
    {
        parent::__construct(
            'Tax with id "{{ id }}" not found.',
            ['id' => $taxId]
        );
    }

    public function getErrorCode(): string
    {
        return 'SYSTEM__TAX_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_NOT_FOUND;
    }
}
