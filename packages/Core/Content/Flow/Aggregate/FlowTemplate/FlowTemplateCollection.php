<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Aggregate\FlowTemplate;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<FlowTemplateEntity>
 */
#[Package('services-settings')]
class FlowTemplateCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'flow_template_collection';
    }

    protected function getExpectedClass(): string
    {
        return FlowTemplateEntity::class;
    }
}
