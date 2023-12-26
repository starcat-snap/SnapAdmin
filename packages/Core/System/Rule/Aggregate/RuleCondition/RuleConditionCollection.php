<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Rule\Aggregate\RuleCondition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<RuleConditionEntity>
 */
#[Package('services-settings')]
class RuleConditionCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'rule_condition_collection';
    }

    protected function getExpectedClass(): string
    {
        return RuleConditionEntity::class;
    }
}
