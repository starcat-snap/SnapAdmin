<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Extension;

use SnapAdmin\Frontend\Content\Category\CategoryDefinition;
use SnapAdmin\Frontend\Content\Category\CategoryEntity;
use SnapAdmin\Frontend\Content\Category\Service\AbstractCategoryUrlGenerator;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Frontend\Channel\ChannelContext;
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
        $channel = null;
        if (\array_key_exists('context', $twigContext) && $twigContext['context'] instanceof ChannelContext) {
            $channel = $twigContext['context']->getChannel();
        }

        return $this->categoryUrlGenerator->generate($category, $channel);
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
