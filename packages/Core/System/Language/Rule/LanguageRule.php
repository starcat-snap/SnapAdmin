<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\Rule;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\Exception\UnsupportedOperatorException;
use SnapAdmin\Core\Framework\Rule\Exception\UnsupportedValueException;
use SnapAdmin\Core\Framework\Rule\Rule;
use SnapAdmin\Core\Framework\Rule\RuleComparison;
use SnapAdmin\Core\Framework\Rule\RuleConfig;
use SnapAdmin\Core\Framework\Rule\RuleConstraints;
use SnapAdmin\Core\Framework\Rule\RuleScope;
use SnapAdmin\Core\System\Language\LanguageDefinition;

#[Package('business-ops')]
class LanguageRule extends Rule
{
    final public const RULE_NAME = 'language';

    /**
     * @param list<string>|null $languageIds
     * @internal
     *
     */
    public function __construct(
        protected string $operator = self::OPERATOR_EQ,
        protected ?array $languageIds = null
    )
    {
        parent::__construct();
    }

    /**
     * @throws UnsupportedOperatorException|UnsupportedValueException
     */
    public function match(RuleScope $scope): bool
    {
        if ($this->languageIds === null) {
            throw new UnsupportedValueException(\gettype($this->languageIds), self::class);
        }

        return RuleComparison::uuids([$scope->getContext()->getLanguageId()], $this->languageIds, $this->operator);
    }

    public function getConstraints(): array
    {
        return [
            'operator' => RuleConstraints::uuidOperators(false),
            'languageIds' => RuleConstraints::uuids(),
        ];
    }

    public function getConfig(): RuleConfig
    {
        return (new RuleConfig())
            ->operatorSet(RuleConfig::OPERATOR_SET_STRING, false, true)
            ->entitySelectField('languageIds', LanguageDefinition::ENTITY_NAME, true);
    }
}
