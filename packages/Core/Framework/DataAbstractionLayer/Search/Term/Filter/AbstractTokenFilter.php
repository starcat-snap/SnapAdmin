<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Term\Filter;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Contracts\Service\ResetInterface;

#[Package('core')]
abstract class AbstractTokenFilter implements ResetInterface
{
    public function reset(): void
    {
        $this->getDecorated()->reset();
    }

    abstract public function getDecorated(): AbstractTokenFilter;

    /**
     * @param list<string> $tokens
     *
     * @return list<string>
     */
    abstract public function filter(array $tokens, Context $context): array;
}
