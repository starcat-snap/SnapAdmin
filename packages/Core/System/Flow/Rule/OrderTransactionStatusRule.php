<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Rule;

use SnapAdmin\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionEntity;
use SnapAdmin\Core\Checkout\Order\Aggregate\OrderTransaction\OrderTransactionStates;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\Framework\Rule\RuleComparison;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;

#[Package('services-settings')]
class OrderTransactionStatusRule extends FlowRule
{
    public const RULE_NAME = 'orderTransactionStatus';

    /**
     * @var array<string>
     */
    protected array $salutationIds = [];

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
        if (!$scope instanceof FlowRuleScope || $this->stateIds === null) {
            return false;
        }

        if (!$transactions = $scope->getOrder()->getTransactions()) {
            return false;
        }

        /** @var OrderTransactionEntity $last */
        $last = $transactions->last();
        $paymentMethodId = $last->getStateId();

        foreach ($transactions->getElements() as $transaction) {
            $technicalName = $transaction->getStateMachineState()?->getTechnicalName();
            if ($technicalName !== null
                && $technicalName !== OrderTransactionStates::STATE_FAILED
                && $technicalName !== OrderTransactionStates::STATE_CANCELLED
            ) {
                $paymentMethodId = $transaction->getStateId();

                break;
            }
        }

        return RuleComparison::stringArray($paymentMethodId, $this->stateIds, $this->operator);
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
                                'value' => 'order_transaction.state',
                            ],
                        ],
                    ],
                ]
            );
    }
}
