<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\StaticAnalyze\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\AssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RuleAreas;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @implements Rule<MethodCall>
 *
 * @internal
 */
#[Package('core')]
class RuleAreasFlagNotAllowedRule implements Rule
{
    use InTestClassTrait;

    public function __construct(private readonly ReflectionProvider $reflectionProvider)
    {
    }

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     *
     * @return array<array-key, RuleError|string>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        if ($this->isInTestClass($scope)) {
            return [];
        }

        if (!$node->name instanceof Identifier) {
            return [];
        }

        if ((string) $node->name !== 'addFlags') {
            return [];
        }

        $class = $scope->getClassReflection();

        if ($class === null) {
            return [];
        }

        foreach ($node->getArgs() as $arg) {
            if ($this->resolveClassName($arg->value) !== RuleAreas::class) {
                continue;
            }

            $fieldClassName = $this->resolveClassName($node->var);

            if (!$fieldClassName || !$this->reflectionProvider->hasClass($fieldClassName)) {
                continue;
            }

            $mockedClass = $this->reflectionProvider->getClass($fieldClassName);
            if (!$mockedClass->isSubclassOf(AssociationField::class)) {
                return [
                    'RuleAreas flag may only be added on instances of AssociationField',
                ];
            }
        }

        return [];
    }

    private function resolveClassName(Node $node): ?string
    {
        switch (true) {
            case $node instanceof New_:
                if ($node->class instanceof Name) {
                    return (string) $node->class;
                }

                return null;
            default:
                return null;
        }
    }
}
