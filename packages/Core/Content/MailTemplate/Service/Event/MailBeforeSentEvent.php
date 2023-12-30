<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Service\Event;

use Monolog\Level;
use SnapAdmin\Core\Content\Flow\Dispatching\Action\FlowMailVariables;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\MessageAware;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\ScalarValuesAware;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\ArrayType;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Event\EventData\ObjectType;
use SnapAdmin\Core\Framework\Event\EventData\ScalarValueType;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Log\LogAware;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\Mime\Email;
use Symfony\Contracts\EventDispatcher\Event;

#[Package('services-settings')]
class MailBeforeSentEvent extends Event implements LogAware, MessageAware, ScalarValuesAware, FlowEventAware
{
    final public const EVENT_NAME = 'mail.after.create.message';

    /**
     * @param array<string, mixed> $data
     */
    public function __construct(
        private readonly array $data,
        private readonly Email $message,
        private readonly Context $context,
        private readonly ?string $eventName = null
    ) {
    }

    /**
     * @return array<string, scalar|array<mixed>|null>
     */
    public function getValues(): array
    {
        return [FlowMailVariables::DATA => $this->data];
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('data', new ArrayType(new ScalarValueType(ScalarValueType::TYPE_STRING)))
            ->add('message', new ObjectType());
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    /**
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    public function getMessage(): Email
    {
        return $this->message;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function getLogData(): array
    {
        $data = $this->data;
        unset($data['binAttachments']);

        return [
            'data' => $data,
            'eventName' => $this->eventName,
            'message' => $this->message,
        ];
    }

    public function getLogLevel(): Level
    {
        return Level::Info;
    }
}
