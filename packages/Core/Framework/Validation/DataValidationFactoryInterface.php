<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Validation;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Frontend\Channel\ChannelContext;

#[Package('core')]
interface DataValidationFactoryInterface
{
    public function create(ChannelContext $context): DataValidationDefinition;

    public function update(ChannelContext $context): DataValidationDefinition;
}
