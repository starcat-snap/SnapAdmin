<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class PluginNotActivatedException extends SnapAdminHttpException
{
    public function __construct(string $pluginName)
    {
        parent::__construct(
            'Plugin "{{ plugin }}" is not activated.',
            ['plugin' => $pluginName]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PLUGIN_NOT_ACTIVATED';
    }
}
