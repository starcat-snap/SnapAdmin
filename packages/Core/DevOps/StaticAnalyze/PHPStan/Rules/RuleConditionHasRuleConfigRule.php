<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\StaticAnalyze\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use SnapAdmin\Core\Checkout\Cart\Rule\AlwaysValidRule;
use SnapAdmin\Core\Checkout\Cart\Rule\GoodsCountRule;
use SnapAdmin\Core\Checkout\Cart\Rule\GoodsPriceRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemCustomFieldRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemGoodsTotalRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemGroupRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemInCategoryRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemPropertyRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemPurchasePriceRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemWithQuantityRule;
use SnapAdmin\Core\Checkout\Cart\Rule\LineItemWrapperRule;
use SnapAdmin\Core\Checkout\Customer\Rule\BillingZipCodeRule;
use SnapAdmin\Core\Checkout\Customer\Rule\CustomerCustomFieldRule;
use SnapAdmin\Core\Checkout\Customer\Rule\ShippingZipCodeRule;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Rule\Container\AndRule;
use SnapAdmin\Core\Framework\Rule\Container\Container;
use SnapAdmin\Core\Framework\Rule\Container\FilterRule;
use SnapAdmin\Core\Framework\Rule\Container\MatchAllLineItemsRule;
use SnapAdmin\Core\Framework\Rule\Container\NotRule;
use SnapAdmin\Core\Framework\Rule\Container\OrRule;
use SnapAdmin\Core\Framework\Rule\Container\XorRule;
use SnapAdmin\Core\Framework\Rule\Container\ZipCodeRule;
use SnapAdmin\Core\Framework\Rule\DateRangeRule;
use SnapAdmin\Core\Framework\Rule\Rule as SnapAdminRule;
use SnapAdmin\Core\Framework\Rule\ScriptRule;
use SnapAdmin\Core\Framework\Rule\SimpleRule;
use SnapAdmin\Core\Framework\Rule\TimeRangeRule;
use SnapAdmin\Core\Test\Stub\Rule\FalseRule;
use SnapAdmin\Core\Test\Stub\Rule\TrueRule;

/**
 * @implements Rule<InClassNode>
 *
 * @internal
 */
#[Package('core')]
class RuleConditionHasRuleConfigRule implements Rule
{
    /**
     * @var list<string>
     */
    private array $rulesAllowedToBeWithoutConfig = [
        ZipCodeRule::class,
        FilterRule::class,
        Container::class,
        AndRule::class,
        NotRule::class,
        OrRule::class,
        XorRule::class,
        MatchAllLineItemsRule::class,
        ScriptRule::class,
        DateRangeRule::class,
        SimpleRule::class,
        TimeRangeRule::class,
        GoodsCountRule::class,
        GoodsPriceRule::class,
        LineItemRule::class,
        LineItemWithQuantityRule::class,
        LineItemWrapperRule::class,
        BillingZipCodeRule::class,
        ShippingZipCodeRule::class,
        AlwaysValidRule::class,
        LineItemPropertyRule::class,
        LineItemPurchasePriceRule::class,
        LineItemInCategoryRule::class,
        LineItemCustomFieldRule::class,
        LineItemGoodsTotalRule::class,
        CustomerCustomFieldRule::class,
        LineItemGroupRule::class,
        FalseRule::class,
        TrueRule::class,
    ];

    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    /**
     * @param InClassNode $node
     *
     * @return array<array-key, RuleError|string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isRuleClass($scope) || $this->isAllowed($scope) || $this->isValid($scope)) {
            if ($this->isAllowed($scope) && $this->isValid($scope)) {
                return ['This class is implementing the getConfig function and has a own admin component. Remove getConfig or the component.'];
            }

            return [];
        }

        return ['This class has to implement getConfig or implement a new admin component.'];
    }

    private function isValid(Scope $scope): bool
    {
        $class = $scope->getClassReflection();
        if ($class === null || !$class->hasMethod('getConfig')) {
            return false;
        }

        $declaringClass = $class->getMethod('getConfig', $scope)->getDeclaringClass();

        return $declaringClass->getName() !== SnapAdminRule::class;
    }

    private function isAllowed(Scope $scope): bool
    {
        $class = $scope->getClassReflection();
        if ($class === null) {
            return false;
        }

        return \in_array($class->getName(), $this->rulesAllowedToBeWithoutConfig, true);
    }

    private function isRuleClass(Scope $scope): bool
    {
        $class = $scope->getClassReflection();
        if ($class === null) {
            return false;
        }

        $namespace = $class->getName();
        if (!\str_contains($namespace, 'SnapAdmin\\Tests\\Unit\\') && !\str_contains($namespace, 'SnapAdmin\\Tests\\Migration\\')) {
            return false;
        }

        return $class->isSubclassOf(SnapAdminRule::class);
    }
}
