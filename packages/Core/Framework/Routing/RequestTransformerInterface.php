<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Request;

#[Package('core')]
interface RequestTransformerInterface
{
    public function transform(Request $request): Request;

    /**
     * Return only attributes that should be inherited by subrequests
     *
     * @return array<string, mixed>
     */
    public function extractInheritableAttributes(Request $sourceRequest): array;
}
