<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching;

use SnapAdmin\Core\Content\Flow\Dispatching\Storer\FlowStorer;
use SnapAdmin\Core\Framework\Api\Context\SystemSource;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('services-settings')]
class FlowFactory
{
    /**
     * @param FlowStorer[] $storer
     */
    public function __construct(private $storer)
    {
    }

    public function create(FlowEventAware $event): StorableFlow
    {
        $stored = $this->getStored($event);

        return $this->restore($event->getName(), $event->getContext(), $stored);
    }

    /**
     * @param array<string, mixed> $stored
     * @param array<string, mixed> $data
     */
    public function restore(string $name, Context $context, array $stored = [], array $data = []): StorableFlow
    {
        $systemContext = new Context(
            new SystemSource(),
            $context->getRuleIds(),
            $context->getLanguageIdChain(),
            $context->getVersionId(),
            $context->considerInheritance(),
        );
        $systemContext->setExtensions($context->getExtensions());

        $flow = new StorableFlow($name, $systemContext, $stored, $data);

        foreach ($this->storer as $storer) {
            $storer->restore($flow);
        }

        return $flow;
    }

    /**
     * @return array<string, mixed>
     */
    private function getStored(FlowEventAware $event): array
    {
        $stored = [];
        foreach ($this->storer as $storer) {
            $stored = $storer->store($event, $stored);
        }

        return $stored;
    }
}
