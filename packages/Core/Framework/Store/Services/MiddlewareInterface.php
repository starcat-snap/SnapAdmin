<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use Psr\Http\Message\ResponseInterface;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('services-settings')]
interface MiddlewareInterface
{
    public function __invoke(ResponseInterface $response): ResponseInterface;
}
