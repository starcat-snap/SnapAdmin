<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Update\Event;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('system-settings')]
abstract class UpdateEvent extends Event
{
    public function __construct(private readonly Context $context)
    {
    }

    public function getContext(): Context
    {
        return $this->context;
    }
}
