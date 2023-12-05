<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Plugin\PluginEntity;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class PluginHasActiveDependantsException extends SnapAdminHttpException
{
    /**
     * @param PluginEntity[] $dependants
     */
    public function __construct(
        string $dependency,
        array  $dependants
    )
    {
        $dependantNameList = array_map(static fn($plugin) => sprintf('"%s"', $plugin->getName()), $dependants);

        parent::__construct(
            'The following plugins depend on "{{ dependency }}": {{ dependantNames }}. They need to be deactivated before "{{ dependency }}" can be deactivated or uninstalled itself.',
            [
                'dependency' => $dependency,
                'dependants' => $dependants,
                'dependantNames' => implode(', ', $dependantNameList),
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PLUGIN_HAS_DEPENDANTS';
    }
}
