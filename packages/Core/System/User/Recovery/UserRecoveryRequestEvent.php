<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User\Recovery;

use SnapAdmin\Core\Content\Flow\Dispatching\Action\FlowMailVariables;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\ResetUrlAware;
use SnapAdmin\Core\Content\Flow\Dispatching\Aware\ScalarValuesAware;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Event\EventData\EntityType;
use SnapAdmin\Core\Framework\Event\EventData\EventDataCollection;
use SnapAdmin\Core\Framework\Event\EventData\MailRecipientStruct;
use SnapAdmin\Core\Framework\Event\EventData\ScalarValueType;
use SnapAdmin\Core\Framework\Event\FlowEventAware;
use SnapAdmin\Core\Framework\Event\MailAware;
use SnapAdmin\Core\Framework\Event\UserAware;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryEntity;
use SnapAdmin\Core\System\User\UserEntity;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @deprecated tag:v6.6.0 - reason:class-hierarchy-change - ResetUrlAware is deprecated and will be removed in v6.6.0
 */
#[Package('system-settings')]
class UserRecoveryRequestEvent extends Event implements UserAware, MailAware, ResetUrlAware, ScalarValuesAware, FlowEventAware
{
    final public const EVENT_NAME = 'user.recovery.request';

    private ?MailRecipientStruct $mailRecipientStruct = null;

    public function __construct(
        private readonly UserRecoveryEntity $userRecovery,
        private readonly string $resetUrl,
        private readonly Context $context
    ) {
    }

    public function getName(): string
    {
        return self::EVENT_NAME;
    }

    public function getUserRecovery(): UserRecoveryEntity
    {
        return $this->userRecovery;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public static function getAvailableData(): EventDataCollection
    {
        return (new EventDataCollection())
            ->add('userRecovery', new EntityType(UserRecoveryDefinition::class))
            ->add('resetUrl', new ScalarValueType('string'))
        ;
    }

    /**
     * @return array<string, scalar|array<mixed>|null>
     */
    public function getValues(): array
    {
        return [
            FlowMailVariables::RESET_URL => $this->resetUrl,
        ];
    }

    public function getMailStruct(): MailRecipientStruct
    {
        if (!$this->mailRecipientStruct instanceof MailRecipientStruct) {
            /** @var UserEntity $user */
            $user = $this->userRecovery->getUser();

            $this->mailRecipientStruct = new MailRecipientStruct([
                $user->getEmail() => $user->getFirstName() . ' ' . $user->getLastName(),
            ]);
        }

        return $this->mailRecipientStruct;
    }

    public function getSalesChannelId(): ?string
    {
        return null;
    }

    public function getResetUrl(): string
    {
        return $this->resetUrl;
    }

    public function getUserId(): string
    {
        return $this->getUserRecovery()->getId();
    }
}
