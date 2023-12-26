<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Flow\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\CustomFieldRule;
use SnapAdmin\Core\Framework\Rule\Exception\UnsupportedOperatorException;
use SnapAdmin\Core\Framework\Rule\FlowRule;
use SnapAdmin\Core\Framework\Rule\RuleScope;

#[Package('services-settings')]
class OrderCustomFieldRule extends FlowRule
{
    final public const RULE_NAME = 'orderCustomField';

    /**
     * @var array<string|int|bool|float>|string|int|bool|float|null
     */
    protected array|string|int|bool|null|float $renderedFieldValue = null;

    /**
     * @param array<string, string|array<string, string>> $renderedField
     *
     * @internal
     */
    public function __construct(
        protected string $operator = self::OPERATOR_EQ,
        protected array $renderedField = []
    ) {
        parent::__construct();
    }

    /**
     * @throws UnsupportedOperatorException
     */
    public function match(RuleScope $scope): bool
    {
        if (!$scope instanceof FlowRuleScope) {
            return false;
        }

        $orderCustomFields = $scope->getOrder()->getCustomFields() ?? [];

        return CustomFieldRule::match($this->renderedField, $this->renderedFieldValue, $this->operator, $orderCustomFields);
    }

    public function getConstraints(): array
    {
        return CustomFieldRule::getConstraints($this->renderedField);
    }
}
