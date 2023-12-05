<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Exception;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;

#[Package('core')]
class MissingFieldSerializerException extends SnapAdminHttpException
{
    public function __construct(Field $field)
    {
        parent::__construct('No field serializer class found for field class "{{ class }}".', ['class' => $field::class]);
    }

    public function getErrorCode(): string
    {
        return 'FRAMEWORK__MISSING_FIELD_SERIALIZER';
    }
}
