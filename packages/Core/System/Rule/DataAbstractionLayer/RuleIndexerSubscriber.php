<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Rule\DataAbstractionLayer;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Doctrine\RetryableQuery;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostActivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostDeactivateEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostInstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUninstallEvent;
use SnapAdmin\Core\Framework\Plugin\Event\PluginPostUpdateEvent;
use SnapAdmin\Core\System\Rule\RuleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @internal
 */
#[Package('services-settings')]
class RuleIndexerSubscriber implements EventSubscriberInterface
{
    /**
     * @internal
     */
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PluginPostInstallEvent::class => 'refreshPlugin',
            PluginPostActivateEvent::class => 'refreshPlugin',
            PluginPostUpdateEvent::class => 'refreshPlugin',
            PluginPostDeactivateEvent::class => 'refreshPlugin',
            PluginPostUninstallEvent::class => 'refreshPlugin',
            RuleEvents::RULE_WRITTEN_EVENT => 'onRuleWritten',
        ];
    }

    public function refreshPlugin(): void
    {
        // Delete the payload and invalid flag of all rules
        $update = new RetryableQuery(
            $this->connection,
            $this->connection->prepare('UPDATE `rule` SET `payload` = null, `invalid` = 0')
        );
        $update->execute();
    }

    public function onRuleWritten(): void
    {
    }
}
