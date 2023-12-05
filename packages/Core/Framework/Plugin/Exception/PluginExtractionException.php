<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class PluginExtractionException extends SnapAdminHttpException
{
    public function __construct(string $reason)
    {
        parent::__construct(
            'Plugin extraction failed. Error: {{ error }}',
            ['error' => $reason]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PLUGIN_EXTRACTION_FAILED';
    }
}
