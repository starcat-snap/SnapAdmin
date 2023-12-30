<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Service\Event;

use Monolog\Level;
use SnapAdmin\Core\Content\Flow\Dispatching\Action\FlowMailVariables;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\ScalarValuesAware;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\ArrayType;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Event\EventData\ScalarValueType;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\LogAware;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('services-settings')]
class MailSentEvent extends Event implements LogAware, ScalarValuesAware, FlowEventAware
{
    final public const EVENT_NAME = 'mail.sent';

    /**
     * @param array<string, mixed> $recipients
     * @param array<string, mixed> $contents
     */
    public function __construct(
        private readonly string $subject,
        private readonly array $recipients,
        private readonly array $contents,
        private readonly Context $context,
        private readonly ?string $eventName = null
    ) {
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('subject', new ScalarValueType(ScalarValueType::TYPE_STRING))
            ->add('contents', new ScalarValueType(ScalarValueType::TYPE_STRING))
            ->add('recipients', new ArrayType(new ScalarValueType(ScalarValueType::TYPE_STRING)));
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * @return array<string, scalar|array<mixed>|null>
     */
    public function getValues(): array
    {
        return [
            FlowMailVariables::SUBJECT => $this->subject,
            FlowMailVariables::CONTENTS => $this->contents,
            FlowMailVariables::RECIPIENTS => $this->recipients,
        ];
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return array<string, mixed>
     */
    public function getContents(): array
    {
        return $this->contents;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function getLogData(): array
    {
        return [
            'eventName' => $this->eventName,
            'subject' => $this->subject,
            'recipients' => $this->recipients,
            'contents' => $this->contents,
        ];
    }

    public function getLogLevel(): Level
    {
        return Level::Info;
    }
}
