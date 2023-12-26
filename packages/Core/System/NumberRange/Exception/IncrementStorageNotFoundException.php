<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\NumberRange\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('checkout')]
class IncrementStorageNotFoundException extends SnapAdminHttpException
{
    /**
     * @param array<string> $availableStorages
     */
    public function __construct(
        string $configuredStorage,
        array $availableStorages
    ) {
        parent::__construct(
            'The number range increment storage "{{ configuredStorage }}" is not available. Available storages are: "{{ availableStorages }}".',
            [
                'configuredStorage' => $configuredStorage,
                'availableStorages' => implode('", "', $availableStorages),
            ]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__INCREMENT_STORAGE_NOT_FOUND';
    }
}
