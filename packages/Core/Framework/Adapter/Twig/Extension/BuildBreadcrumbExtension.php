<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Twig\Extension;

use SnapAdmin\Frontend\Content\Category\CategoryCollection;
use SnapAdmin\Frontend\Content\Category\CategoryEntity;
use SnapAdmin\Frontend\Content\Category\Service\CategoryBreadcrumbBuilder;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Frontend\Channel\ChannelContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

#[Package('core')]
class BuildBreadcrumbExtension extends AbstractExtension
{
    /**
     * @internal
     */
    public function __construct(
        private readonly CategoryBreadcrumbBuilder $categoryBreadcrumbBuilder,
        private readonly EntityRepository          $categoryRepository
    )
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sw_breadcrumb_full', $this->getFullBreadcrumb(...), ['needs_context' => true]),
        ];
    }

    /**
     * @param array<string, mixed> $twigContext
     *
     * @return array<string, CategoryEntity>
     */
    public function getFullBreadcrumb(array $twigContext, CategoryEntity $category, Context $context): array
    {
        $channel = null;
        if (\array_key_exists('context', $twigContext) && $twigContext['context'] instanceof ChannelContext) {
            $channel = $twigContext['context']->getChannel();
        }

        $seoBreadcrumb = $this->categoryBreadcrumbBuilder->build($category, $channel);

        if ($seoBreadcrumb === null) {
            return [];
        }

        /** @var list<string> $categoryIds */
        $categoryIds = array_keys($seoBreadcrumb);
        if (empty($categoryIds)) {
            return [];
        }

        $criteria = new Criteria($categoryIds);
        $criteria->setTitle('breadcrumb-extension');
        /** @var CategoryCollection $categories */
        $categories = $this->categoryRepository->search($criteria, $context)->getEntities();

        $breadcrumb = [];
        foreach ($categoryIds as $categoryId) {
            if ($categories->get($categoryId) === null) {
                continue;
            }

            $breadcrumb[$categoryId] = $categories->get($categoryId);
        }

        return $breadcrumb;
    }
}
