<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Plugin\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class PluginBaseClassNotFoundException extends SnapAdminHttpException
{
    public function __construct(string $baseClass)
    {
        parent::__construct(
            'The class "{{ baseClass }}" is not found. Probably an class loader error. Check your plugin composer.json',
            ['baseClass' => $baseClass]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__PLUGIN_BASE_CLASS_NOT_FOUND';
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
