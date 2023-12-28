<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Dispatching\Aware;

use SnapAdmin\Core\Framework\Event\IsFlowEventAware;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Mime\Email;

#[Package('services-settings')]
#[IsFlowEventAware]
interface MessageAware
{
    public const MESSAGE = 'message';

    public function getMessage(): Email;
}
