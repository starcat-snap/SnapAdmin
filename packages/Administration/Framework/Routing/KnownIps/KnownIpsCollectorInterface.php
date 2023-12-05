<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Framework\Routing\KnownIps;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;

#[Package('administration')]
interface KnownIpsCollectorInterface
{
    public function collectIps(Request $request): array;
}
