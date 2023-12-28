<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;

#[Package('services-settings')]
class OrderCreatedByAdminRule extends FlowRule
{
    final public const RULE_NAME = 'orderCreatedByAdmin';

    /**
     * @internal
     */
    public function __construct(private bool $shouldOrderBeCreatedByAdmin = true)
    {
        parent::__construct();
    }

    public function match(RuleScope $scope): bool
    {
        if (!$scope instanceof FlowRuleScope) {
            return false;
        }

        return $this->shouldOrderBeCreatedByAdmin === (bool) $scope->getOrder()->getCreatedById();
    }

    public function getConstraints(): array
    {
        return [
            'shouldOrderBeCreatedByAdmin' => RuleConstraints::bool(true),
        ];
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())->booleanField('shouldOrderBeCreatedByAdmin');
    }
}
