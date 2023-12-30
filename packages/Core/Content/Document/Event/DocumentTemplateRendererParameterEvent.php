<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Event;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Struct\ExtendableTrait;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('content')]
class DocumentTemplateRendererParameterEvent extends Event
{
    use ExtendableTrait;

    public function __construct(private readonly array $parameters)
    {
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
