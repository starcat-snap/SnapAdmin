<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Validation;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\GenericEvent;
use SnapAdmin\Core\Framework\Event\SnapAdminEvent;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Validation\DataBag\DataBag;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('core')]
class BuildValidationEvent extends Event implements SnapAdminEvent, GenericEvent
{
    public function __construct(
        private readonly DataValidationDefinition $definition,
        private readonly DataBag $data,
        private readonly Context $context
    ) {
    }

    public function getName(): string
    {
        return 'framework.validation.' . $this->definition->getName();
    }

    public function getDefinition(): DataValidationDefinition
    {
        return $this->definition;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getData(): DataBag
    {
        return $this->data;
    }
}
