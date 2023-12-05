<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Requirement\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
abstract class RequirementException extends SnapAdminHttpException
{
    public function getStatusCode(): int
    {
        return Response::HTTP_FAILED_DEPENDENCY;
    }
}
