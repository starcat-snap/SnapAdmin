<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class DecodeByHydratorException extends SnapAdminHttpException
{
    public function __construct(Field $field)
    {
        parent::__construct(
            'Decoding of {{ fieldClass }} is handled by the entity hydrator.',
            ['fieldClass' => $field::class]
        );
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__DECODING_HANDLED_BY_ENTITY_HYDRATOR';
    }
}
