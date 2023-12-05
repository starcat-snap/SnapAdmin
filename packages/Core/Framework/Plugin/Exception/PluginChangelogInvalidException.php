<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

/**
 * @deprecated tag:v6.6.0 - will be removed without a replacement
 */
#[Package('core')]
class PluginChangelogInvalidException extends SnapAdminHttpException
{
    public function __construct(string $changelogPath)
    {
        Feature::triggerDeprecationOrThrow('v6.6.0.0', Feature::deprecatedMethodMessage(self::class, __METHOD__, '6.6.0'));

        parent::__construct(
            'The changelog of "{{ changelogPath }}" is invalid.',
            ['changelogPath' => $changelogPath]
        );
    }

    public function getErrorCode(): string
    {
        Feature::triggerDeprecationOrThrow('v6.6.0.0', Feature::deprecatedMethodMessage(self::class, __METHOD__, '6.6.0'));

        return 'FRAMEWORK__PLUGIN_CHANGELOG_INVALID';
    }
}
