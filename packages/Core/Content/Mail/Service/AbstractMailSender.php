<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Mail\Service;

use SnapAdmin\Core\Content\MailTemplate\Exception\MailTransportFailedException;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Email;

#[Package('system-settings')]
abstract class AbstractMailSender
{
    abstract public function getDecorated(): AbstractMailSender;

    /**
     * @throws MailTransportFailedException
     */
    abstract public function send(Email $email, ?Envelope $envelope = null): void;
}
