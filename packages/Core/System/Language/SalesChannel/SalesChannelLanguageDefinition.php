<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\SalesChannel;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Language\LanguageDefinition;
use SnapAdmin\Core\System\SalesChannel\Entity\SalesChannelDefinitionInterface;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

#[Package('buyers-experience')]
class SalesChannelLanguageDefinition extends LanguageDefinition implements SalesChannelDefinitionInterface
{
    public function processCriteria(Criteria $criteria, SalesChannelContext $context): void
    {
        $criteria->addFilter(new EqualsFilter('language.salesChannels.id', $context->getSalesChannel()->getId()));
    }
}
