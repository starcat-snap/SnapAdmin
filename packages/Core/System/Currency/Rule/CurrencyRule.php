<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Currency\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\Framework\Rule\RuleComparison;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;
use SnapAdmin\Core\System\Currency\CurrencyDefinition;

#[Package('business-ops')]
class CurrencyRule extends Rule
{
    final public const RULE_NAME = 'currency';

    /**
     * @internal
     *
     * @param list<string>|null $currencyIds
     */
    public function __construct(
        protected string $operator = self::OPERATOR_EQ,
        protected ?array $currencyIds = null
    ) {
        parent::__construct();
    }

    public function match(RuleScope $scope): bool
    {
        return RuleComparison::uuids([$scope->getContext()->getCurrencyId()], $this->currencyIds, $this->operator);
    }

    public function getConstraints(): array
    {
        return [
            'currencyIds' => RuleConstraints::uuids(),
            'operator' => RuleConstraints::uuidOperators(false),
        ];
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())
            ->operatorSet(RuleConfig::OPERATOR_SET_STRING, false, true)
            ->entitySelectField('currencyIds', CurrencyDefinition::ENTITY_NAME, true);
    }
}
