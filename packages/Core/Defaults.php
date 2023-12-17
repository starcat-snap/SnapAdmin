<?php declare(strict_types=1);

namespace SnapAdmin\Core;

use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 * System wide defaults that are fixed for performance measures
 */
#[Package('core')]
class Defaults
{
    /**
     * Don't depend on this being zh-CN, the underlying language can be overwritten by the installer!
     */
    public const LANGUAGE_SYSTEM = '2fbb5fe2e29a4d70aa5854ce7ce3e20b';

    public const LIVE_VERSION = '0fa91ce3e96a4bc2be4bd9ce752c3425';

    public const STORAGE_DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Do not use STORAGE_DATE_FORMAT for createdAt fields, use STORAGE_DATE_TIME_FORMAT instead
     */
    public const STORAGE_DATE_FORMAT = 'Y-m-d';
}
