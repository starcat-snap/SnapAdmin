<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;

#[Package('core')]
interface RequestContextResolverInterface
{
    public function resolve(Request $request): void;
}
