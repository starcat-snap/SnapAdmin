<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Webhook\Hookable;

use SnapAdmin\Core\Checkout\Customer\Aggregate\CustomerAddress\CustomerAddressDefinition;
use SnapAdmin\Core\Checkout\Customer\CustomerDefinition;
use SnapAdmin\Core\Checkout\Document\DocumentDefinition;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderAddress\OrderAddressDefinition;
use SnapAdmin\Core\Checkout\Order\OrderDefinition;
use SnapAdmin\Core\Content\Category\CategoryDefinition;
use SnapAdmin\Core\Content\Media\MediaDefinition;
use SnapAdmin\Core\Content\Product\Aggregate\ProductPrice\ProductPriceDefinition;
use SnapAdmin\Core\Content\Product\ProductDefinition;
use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleDefinition;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\Event\BusinessEventCollector;
use SnapAdmin\Core\Framework\Event\BusinessEventDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Webhook\Hookable;
use SnapAdmin\Core\System\SalesChannel\Aggregate\SalesChannelDomain\SalesChannelDomainDefinition;
use SnapAdmin\Core\System\SalesChannel\SalesChannelDefinition;

/**
 * @internal only for use by the app-system
 */
#[Package('core')]
class HookableEventCollector
{
    final public const HOOKABLE_ENTITIES = [
        ProductDefinition::ENTITY_NAME,
        ProductPriceDefinition::ENTITY_NAME,
        CategoryDefinition::ENTITY_NAME,
        SalesChannelDefinition::ENTITY_NAME,
        SalesChannelDomainDefinition::ENTITY_NAME,
        CustomerDefinition::ENTITY_NAME,
        CustomerAddressDefinition::ENTITY_NAME,
        OrderDefinition::ENTITY_NAME,
        OrderAddressDefinition::ENTITY_NAME,
        DocumentDefinition::ENTITY_NAME,
        MediaDefinition::ENTITY_NAME,
    ];

    private const PRIVILEGES = 'privileges';

    /**
     * @var string[][][]
     */
    private array $hookableEventNamesWithPrivileges = [];

    public function __construct(
        private readonly BusinessEventCollector $businessEventCollector,
        private readonly DefinitionInstanceRegistry $definitionRegistry
    ) {
    }

    public function getHookableEventNamesWithPrivileges(Context $context): array
    {
        if (!$this->hookableEventNamesWithPrivileges) {
            $this->hookableEventNamesWithPrivileges = $this->getEventNamesWithPrivileges($context);
        }

        return $this->hookableEventNamesWithPrivileges;
    }

    /**
     * @return list<string>
     */
    public function getPrivilegesFromBusinessEventDefinition(BusinessEventDefinition $businessEventDefinition): array
    {
        $privileges = [];
        foreach ($businessEventDefinition->getData() as $data) {
            if ($data['type'] !== 'entity') {
                continue;
            }

            $entityName = $this->definitionRegistry->get($data['entityClass'])->getEntityName();
            $privileges[] = $entityName . ':' . AclRoleDefinition::PRIVILEGE_READ;
        }

        return $privileges;
    }

    /**
     * @return array<string, array{privileges: list<string>}>
     */
    public function getEntityWrittenEventNamesWithPrivileges(): array
    {
        $entityWrittenEventNames = [];
        foreach (self::HOOKABLE_ENTITIES as $entity) {
            $privileges = [
                self::PRIVILEGES => [$entity . ':' . AclRoleDefinition::PRIVILEGE_READ],
            ];

            $entityWrittenEventNames[$entity . '.written'] = $privileges;
            $entityWrittenEventNames[$entity . '.deleted'] = $privileges;
        }

        return $entityWrittenEventNames;
    }

    private function getEventNamesWithPrivileges(Context $context): array
    {
        return array_merge(
            $this->getEntityWrittenEventNamesWithPrivileges(),
            $this->getBusinessEventNamesWithPrivileges($context),
            $this->getHookableEventNames()
        );
    }

    private function getHookableEventNames(): array
    {
        return array_reduce(array_values(
            array_map(static fn ($hookableEvent) => [$hookableEvent => [self::PRIVILEGES => []]], Hookable::HOOKABLE_EVENTS)
        ), 'array_merge', []);
    }

    private function getBusinessEventNamesWithPrivileges(Context $context): array
    {
        $response = $this->businessEventCollector->collect($context);

        return array_map(function (BusinessEventDefinition $businessEventDefinition) {
            $privileges = $this->getPrivilegesFromBusinessEventDefinition($businessEventDefinition);

            return [
                self::PRIVILEGES => $privileges,
            ];
        }, $response->getElements());
    }
}
