<?php declare(strict_types=1);

namespace SnapAdmin\Administration\Notification\Extension;

use SnapAdmin\Administration\Notification\NotificationDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityExtension;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Integration\IntegrationDefinition;

#[Package('administration')]
class IntegrationExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            new OneToManyAssociationField('createdNotifications', NotificationDefinition::class, 'created_by_integration_id', 'id')
        );
    }

    public function getDefinitionClass(): string
    {
        return IntegrationDefinition::class;
    }
}
