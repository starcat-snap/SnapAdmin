<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\Struct\ReviewStruct;

/**
 * @internal
 */
#[Package('services-settings')]
abstract class AbstractExtensionStoreLicensesService
{
    abstract public function cancelSubscription(int $licenseId, Context $context): void;

    abstract public function rateLicensedExtension(ReviewStruct $rating, Context $context): void;

    abstract protected function getDecorated(): AbstractExtensionStoreLicensesService;
}
