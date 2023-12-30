<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Renderer;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
final class DocumentRendererConfig
{
    public string $deepLinkCode = '';
}
