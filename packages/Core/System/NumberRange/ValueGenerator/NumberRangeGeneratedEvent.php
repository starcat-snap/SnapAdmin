<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\ValueGenerator;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('system-settings')]
class NumberRangeGeneratedEvent extends Event
{
    final public const NAME = 'number_range.generated';

    public function __construct(
        private string $generatedValue,
        private readonly string $type,
        private readonly Context $context,
        private readonly bool $preview = false
    ) {
    }

    public function getGeneratedValue(): string
    {
        return $this->generatedValue;
    }

    public function setGeneratedValue(string $generatedValue): void
    {
        $this->generatedValue = $generatedValue;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getPreview(): bool
    {
        return $this->preview;
    }
}
