<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Validation;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;

#[Package('core')]
interface DataValidationFactoryInterface
{
    public function create(SalesChannelContext $context): DataValidationDefinition;

    public function update(SalesChannelContext $context): DataValidationDefinition;
}
