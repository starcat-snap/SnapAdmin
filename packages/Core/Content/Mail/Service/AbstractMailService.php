<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Mail\Service;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Mime\Email;

#[Package('system-settings')]
abstract class AbstractMailService
{
    abstract public function getDecorated(): AbstractMailService;

    abstract public function send(array $data, Context $context, array $templateData = []): ?Email;
}
