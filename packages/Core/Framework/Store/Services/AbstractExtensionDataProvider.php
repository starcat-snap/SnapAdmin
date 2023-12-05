<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Services;

use SnapAdmin\Core\Framework\App\AppEntity;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Store\Struct\ExtensionCollection;

/**
 * @internal
 */
#[Package('services-settings')]
abstract class AbstractExtensionDataProvider
{
    abstract public function getInstalledExtensions(Context $context, bool $loadCloudExtensions = true, ?Criteria $searchCriteria = null): ExtensionCollection;

    abstract public function getAppEntityFromTechnicalName(string $technicalName, Context $context): AppEntity;

    abstract public function getAppEntityFromId(string $id, Context $context): AppEntity;

    abstract protected function getDecorated(): AbstractExtensionDataProvider;
}
