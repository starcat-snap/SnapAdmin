<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class MissingReverseAssociation extends SnapAdminHttpException
{
    public function __construct(
        string $source,
        string $target
    ) {
        parent::__construct(
            'Can not find reverse association in entity {{ source }} which should have an association to entity {{ target }}',
            ['source' => $source, 'target' => $target]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__MISSING_REVERSE_ASSOCIATION';
    }
}
