<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet\Filter;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Snippet\Exception\FilterNotFoundException;

#[Package('system-settings')]
class SnippetFilterFactory
{
    /**
     * @internal
     */
    public function __construct(private readonly iterable $filters)
    {
    }

    /**
     * @throws \Exception
     */
    public function getFilter(string $name): SnippetFilterInterface
    {
        /** @var SnippetFilterInterface $filter */
        foreach ($this->filters as $filter) {
            if ($filter->supports($name)) {
                return $filter;
            }
        }

        throw new FilterNotFoundException($name, self::class);
    }
}
