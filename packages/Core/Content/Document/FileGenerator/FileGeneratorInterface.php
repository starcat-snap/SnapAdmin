<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\FileGenerator;

use SnapAdmin\Core\Content\Document\Renderer\RenderedDocument;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
interface FileGeneratorInterface
{
    public function supports(): string;

    public function generate(RenderedDocument $html): string;

    public function getExtension(): string;

    public function getContentType(): string;
}
