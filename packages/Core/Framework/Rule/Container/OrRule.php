<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Rule\Container;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\RuleScope;

#[Package('services-settings')]
class OrRule extends Container
{
    final public const RULE_NAME = 'orContainer';

    public function match(RuleScope $scope): bool
    {
        foreach ($this->rules as $rule) {
            if ($rule->match($scope)) {
                return true;
            }
        }

        return false;
    }
}
