<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\EventListener\Authentication;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use SnapAdmin\Core\Framework\Api\ApiException;
use SnapAdmin\Core\Framework\Api\Util\AccessKeyHelper;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\KernelListenerPriorities;
use SnapAdmin\Core\Framework\Routing\MaintenanceModeResolver;
use SnapAdmin\Core\Framework\Routing\RouteScopeCheckTrait;
use SnapAdmin\Core\Framework\Routing\RouteScopeRegistry;
use SnapAdmin\Core\Framework\Routing\StoreApiRouteScope;
use SnapAdmin\Core\Framework\Util\Json;
use SnapAdmin\Core\Framework\Util\UtilException;
use SnapAdmin\Core\Framework\Uuid\Uuid;
use SnapAdmin\Core\PlatformRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @internal
 *
 * @codeCoverageIgnore Tested via an integration test
 *
 * @see \SnapAdmin\Tests\Integration\Core\Framework\Api\EventListener\SalesChannelAuthenticationListenerTest
 */
#[Package('core')]
class SalesChannelAuthenticationListener implements EventSubscriberInterface
{
    use RouteScopeCheckTrait;

    /**
     * @internal
     */
    public function __construct(
        private readonly Connection $connection,
        private readonly RouteScopeRegistry $routeScopeRegistry,
        private readonly MaintenanceModeResolver $maintenanceModeResolver
    ) {
    }

    /**
     * @return array<string, string|array{0: string, 1: int}|list<array{0: string, 1?: int}>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => [
                'validateRequest',
                KernelListenerPriorities::KERNEL_CONTROLLER_EVENT_PRIORITY_AUTH_VALIDATE,
            ],
        ];
    }

    public function validateRequest(ControllerEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->attributes->get('auth_required', true)) {
            return;
        }

        if (!$this->isRequestScoped($request, StoreApiRouteScope::class)) {
            return;
        }

        $accessKey = $request->headers->get(PlatformRequest::HEADER_ACCESS_KEY);
        if (!$accessKey) {
            throw ApiException::unauthorized(
                'header',
                sprintf('Header "%s" is required.', PlatformRequest::HEADER_ACCESS_KEY)
            );
        }

        $origin = AccessKeyHelper::getOrigin($accessKey);
        if ($origin !== 'sales-channel') {
            throw ApiException::salesChannelNotFound();
        }

        $salesChannelData = $this->getSalesChannelData($accessKey);

        $this->handleMaintenanceMode($request, $salesChannelData);

        $request->attributes->set(PlatformRequest::ATTRIBUTE_SALES_CHANNEL_ID, $salesChannelData['id']);
    }

    protected function getScopeRegistry(): RouteScopeRegistry
    {
        return $this->routeScopeRegistry;
    }

    /**
     * @return array<string, mixed>
     */
    private function getSalesChannelData(string $accessKey): array
    {
        $builder = $this->connection->createQueryBuilder();

        $salesChannelData = $builder->select(
            'sales_channel.id AS id',
            'sales_channel.maintenance AS maintenance',
            'sales_channel.maintenance_ip_whitelist as maintenanceIpWhitelist'
        )
            ->from('sales_channel')
            ->where('sales_channel.access_key = :accessKey')
            ->andWhere('sales_channel.active = :active')
            ->setParameter('accessKey', $accessKey)
            ->setParameter('active', true, Types::BOOLEAN)
            ->executeQuery()
            ->fetchAssociative();

        if (!\is_array($salesChannelData)) {
            throw ApiException::salesChannelNotFound();
        }

        $id = $salesChannelData['id'] ?? null;

        if ($id === null || $id === '') {
            throw ApiException::salesChannelNotFound();
        }

        $salesChannelData['id'] = Uuid::fromBytesToHex($id);

        return $salesChannelData;
    }

    /**
     * @param array<string, mixed> $salesChannelData
     */
    private function handleMaintenanceMode(Request $request, array $salesChannelData): void
    {
        $maintenance = (bool) ($salesChannelData['maintenance'] ?? false);

        if (!$maintenance) {
            return;
        }

        if ($request->attributes->getBoolean(PlatformRequest::ATTRIBUTE_IS_ALLOWED_IN_MAINTENANCE)) {
            return;
        }

        try {
            /** @var string[] $allowedIps */
            $allowedIps = Json::decodeToList((string) ($salesChannelData['maintenanceIpWhitelist'] ?? ''));
        } catch (UtilException $e) {
            return;
        }

        if ($this->maintenanceModeResolver->isClientAllowed($request, $allowedIps)) {
            return;
        }

        throw ApiException::salesChannelInMaintenanceMode();
    }
}
