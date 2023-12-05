<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Extension;

use SnapAdmin\Core\Content\Category\CategoryDefinition;
use SnapAdmin\Core\Content\Category\CategoryEntity;
use SnapAdmin\Core\Content\Category\Service\AbstractCategoryUrlGenerator;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

#[Package('core')]
class CategoryUrlExtension extends AbstractExtension
{
    /**
     * @internal
     */
    public function __construct(
        private readonly RoutingExtension             $routingExtension,
        private readonly AbstractCategoryUrlGenerator $categoryUrlGenerator
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('category_url', $this->getCategoryUrl(...), ['needs_context' => true, 'is_safe_callback' => $this->routingExtension->isUrlGenerationSafe(...)]),
            new TwigFunction('category_linknewtab', $this->isLinkNewTab(...)),
        ];
    }

    public function getCategoryUrl(array $twigContext, CategoryEntity $category): ?string
    {
        $salesChannel = null;
        if (\array_key_exists('context', $twigContext) && $twigContext['context'] instanceof SalesChannelContext) {
            $salesChannel = $twigContext['context']->getSalesChannel();
        }

        return $this->categoryUrlGenerator->generate($category, $salesChannel);
    }

    public function isLinkNewTab(CategoryEntity $categoryEntity): bool
    {
        if ($categoryEntity->getType() !== CategoryDefinition::TYPE_LINK) {
            return false;
        }

        if (!$categoryEntity->getTranslation('linkNewTab')) {
            return false;
        }

        return true;
    }
}
