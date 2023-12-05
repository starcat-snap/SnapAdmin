<?php declare(strict_types=1);

namespace SnapAdmin\Core\DevOps\StaticAnalyze\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\InClassNode;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleError;
use PHPUnit\Framework\TestCase;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @implements Rule<InClassNode>
 *
 * @internal
 */
#[Package('core')]
class PackageAnnotationRule implements Rule
{
    /**
     * @internal
     */
    public const PRODUCT_AREA_MAPPING = [
        'business-ops' => [
            '/SnapAdmin\\\\.*\\\\(Rule|Flow|ProductStream)\\\\/',
            '/SnapAdmin\\\\Core\\\\Framework\\\\(Event)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(Tag)\\\\/',
        ],
        'inventory' => [
            '/SnapAdmin\\\\Core\\\\Content\\\\(Product|ProductExport|Property)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(Currency|Unit)\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Product\\\\/',
        ],
        'content' => [
            '/SnapAdmin\\\\Core\\\\Content\\\\(Media|Category|Cms|ContactForm|LandingPage)\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Cms\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\LandingPage\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Contact\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Navigation\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Menu\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Footer\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Header\\\\/',
        ],
        'system-settings' => [
            '/SnapAdmin\\\\Core\\\\Content\\\\(ImportExport|Mail)\\\\/',
            '/SnapAdmin\\\\Core\\\\Framework\\\\(Update)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(Country|CustomField|Integration|Language|Locale|Snippet|User)\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Country\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Suggest\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Search\\\\/',
        ],
        'sales-channel' => [
            '/SnapAdmin\\\\Core\\\\Content\\\\(MailTemplate|Seo|Sitemap)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(SalesChannel)\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Sitemap\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Captcha\\\\/',
        ],
        'checkout' => [
            '/SnapAdmin\\\\Core\\\\Checkout\\\\(Cart|Payment|Promotion|Shipping)\\\\/',
            '/SnapAdmin\\\\Core\\\\Checkout\\\\(Customer|Document|Order)\\\\/',
            '/SnapAdmin\\\\Core\\\\Content\\\\(Newsletter)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(DeliveryTime|NumberRange|StateMachine)\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(DeliveryTime|Salutation|Tax)\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Checkout\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Account\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Address\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Checkout\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Maintenance\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Newsletter\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Page\\\\Wishlist\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Newsletter\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Pagelet\\\\Wishlist\\\\/',
        ],
        'services-settings' => [
            '/SnapAdmin\\\\Core\\\\Framework\\\\Store\\\\/',
        ],
        'storefront' => [
            '/SnapAdmin\\\\Storefront\\\\Theme\\\\/',
            '/SnapAdmin\\\\Storefront\\\\Controller\\\\/',
            '/SnapAdmin\\\\Storefront\\\\(DependencyInjection|Migration|Event|Exception|Framework|Test)\\\\/',
        ],
        'core' => [
            '/SnapAdmin\\\\Core\\\\Framework\\\\(Adapter|Api|App|Changelog|DataAbstractionLayer|Demodata|DependencyInjection)\\\\/',
            '/SnapAdmin\\\\Core\\\\Framework\\\\(Increment|Log|MessageQueue|Migration|Parameter|Plugin|RateLimiter|Script|Routing|Struct|Util|Uuid|Validation|Webhook)\\\\/',
            '/SnapAdmin\\\\Core\\\\DevOps\\\\/',
            '/SnapAdmin\\\\Core\\\\Installer\\\\/',
            '/SnapAdmin\\\\Core\\\\Maintenance\\\\/',
            '/SnapAdmin\\\\Core\\\\Migration\\\\/',
            '/SnapAdmin\\\\Core\\\\Profiling\\\\/',
            '/SnapAdmin\\\\Elasticsearch\\\\/',
            '/SnapAdmin\\\\Docs\\\\/',
            '/SnapAdmin\\\\Core\\\\System\\\\(Annotation|CustomEntity|DependencyInjection|SystemConfig)\\\\/',
            '/SnapAdmin\\\\.*\\\\(DataAbstractionLayer)\\\\/',
        ],
        'administration' => [
            '/SnapAdmin\\\\Administration\\\\/',
        ],
        'data-services' => [
            '/SnapAdmin\\\\Core\\\\System\\\\UsageData\\\\/',
        ],
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
        if ($node->getClassReflection()->isAnonymous()) {
            return [];
        }

        if ($this->isTestClass($node)) {
            return [];
        }

        $area = $this->getProductArea($node);

        if ($this->hasPackageAnnotation($node)) {
            return [];
        }

        return [sprintf('This class is missing the "#[Package(...)]" attribute (recommendation: %s)', $area ?? 'unknown')];
    }

    private function getProductArea(InClassNode $node): ?string
    {
        $namespace = $node->getClassReflection()->getName();

        foreach (self::PRODUCT_AREA_MAPPING as $area => $regexes) {
            foreach ($regexes as $regex) {
                if (preg_match($regex, $namespace)) {
                    return $area;
                }
            }
        }

        return null;
    }

    private function hasPackageAnnotation(InClassNode $class): bool
    {
        foreach ($class->getOriginalNode()->attrGroups as $group) {
            $attribute = $group->attrs[0];

            /** @var Node\Name\FullyQualified $name */
            $name = $attribute->name;

            if ($name->toString() === Package::class) {
                return true;
            }
        }

        return false;
    }

    private function isTestClass(InClassNode $node): bool
    {
        $namespace = $node->getClassReflection()->getName();

        if (\str_contains($namespace, '\\Tests\\') || \str_contains($namespace, '\\Test\\')) {
            return true;
        }

        $file = (string)$node->getClassReflection()->getFileName();
        if (\str_contains($file, '/tests/') || \str_contains($file, '/Tests/') || \str_contains($file, '/Test/')) {
            return true;
        }

        if ($node->getClassReflection()->getParentClass() === null) {
            return false;
        }

        return $node->getClassReflection()->getParentClass()->getName() === TestCase::class;
    }
}
