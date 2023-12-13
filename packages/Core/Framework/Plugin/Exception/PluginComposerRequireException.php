<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class PluginComposerRequireException extends SnapAdminHttpException
{
    public function __construct(
        string $pluginName,
        string $pluginComposerName,
        string $output
    ) {
        parent::__construct(
            sprintf('Could not execute "composer require" for plugin "{{ pluginName }} ({{ pluginComposerName }}). Output:%s{{ output }}', \PHP_EOL),
            [
                'pluginName' => $pluginName,
                'pluginComposerName' => $pluginComposerName,
                'output' => $output,
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PLUGIN_COMPOSER_REQUIRE';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
