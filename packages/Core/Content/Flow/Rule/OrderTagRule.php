<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Flow\Rule;

use SnapAdmin\Core\Checkout\Order\OrderEntity;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\RuleComparison;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;
use SnapAdmin\Core\System\Tag\TagDefinition;

#[Package('services-settings')]
class OrderTagRule extends FlowRule
{
    final public const RULE_NAME = 'orderTag';

    /**
     * @internal
     *
     * @param list<string>|null $identifiers
     */
    public function __construct(
        protected string $operator = self::OPERATOR_EQ,
        protected ?array $identifiers = null
    ) {
        parent::__construct();
    }

    public function match(RuleScope $scope): bool
    {
        if (!$scope instanceof FlowRuleScope) {
            return false;
        }

        return RuleComparison::uuids($this->extractTagIds($scope->getOrder()), $this->identifiers, $this->operator);
    }

    public function getConstraints(): array
    {
        $constraints = [
            'operator' => RuleConstraints::uuidOperators(),
        ];

        if ($this->operator === self::OPERATOR_EMPTY) {
            return $constraints;
        }

        $constraints['identifiers'] = RuleConstraints::uuids();

        return $constraints;
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())
            ->operatorSet(RuleConfig::OPERATOR_SET_STRING, true, true)
            ->entitySelectField('identifiers', TagDefinition::ENTITY_NAME, true);
    }

    /**
     * @return array<string>
     */
    private function extractTagIds(OrderEntity $order): array
    {
        $tags = $order->getTags();

        if (!$tags) {
            return [];
        }

        return $tags->getIds();
    }
}
