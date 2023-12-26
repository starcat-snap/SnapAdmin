<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;

#[Package('services-settings')]
class OrderTrackingCodeRule extends FlowRule
{
    public const RULE_NAME = 'orderTrackingCode';

    /**
     * @internal
     */
    public function __construct(protected bool $isSet = false)
    {
        parent::__construct();
    }

    public function getConstraints(): array
    {
        return [
            'isSet' => RuleConstraints::bool(true),
        ];
    }

    public function match(RuleScope $scope): bool
    {
        if (!$scope instanceof FlowRuleScope) {
            return false;
        }

        if (!$deliveries = $scope->getOrder()->getDeliveries()) {
            return false;
        }

        $value = 0;
        foreach ($deliveries->getElements() as $delivery) {
            $value += \count(array_filter($delivery->getTrackingCodes()));
        }

        return $value > 0 === $this->isSet;
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())
            ->booleanField('isSet');
    }
}
