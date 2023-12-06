<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\StoreException;

#[Package('services-settings')]
class ExtensionUpdateRequiresConsentAffirmationException extends StoreException
{
}
