<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\Framework\Rule\RuleComparison;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;

#[Package('services-settings')]
class OrderDeliveryStatusRule extends FlowRule
{
    public const RULE_NAME = 'orderDeliveryStatus';

    /**
     * @var array<string>
     */
    public array $salutationIds = [];

    /**
     * @internal
     *
     * @param list<string> $stateIds
     */
    public function __construct(
        public string $operator = Rule::OPERATOR_EQ,
        public ?array $stateIds = null
    ) {
        parent::__construct();
    }

    public function getConstraints(): array
    {
        return [
            'operator' => RuleConstraints::uuidOperators(false),
            'stateIds' => RuleConstraints::uuids(),
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

        $deliveryStateIds = [];
        foreach ($deliveries->getElements() as $delivery) {
            $deliveryStateIds[] = $delivery->getStateId();
        }

        return RuleComparison::uuids($deliveryStateIds, $this->stateIds, $this->operator);
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())
            ->operatorSet(RuleConfig::OPERATOR_SET_STRING, false, true)
            ->entitySelectField(
                'stateIds',
                StateMachineStateDefinition::ENTITY_NAME,
                true,
                [
                    'criteria' => [
                        'associations' => [
                            'stateMachine',
                        ],
                        'filters' => [
                            [
                                'type' => 'equals',
                                'field' => 'state_machine_state.stateMachine.technicalName',
                                'value' => 'order_delivery.state',
                            ],
                        ],
                    ],
                ]
            );
    }
}
