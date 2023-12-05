<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\CreatedByFieldSerializer;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\User\UserDefinition;

#[Package('core')]
class CreatedByField extends FkField
{
    public function __construct(private readonly array $allowedWriteScopes = [Context::SYSTEM_SCOPE])
    {
        parent::__construct('created_by_id', 'createdById', UserDefinition::class);
    }

    public function getAllowedWriteScopes(): array
    {
        return $this->allowedWriteScopes;
    }

    protected function getSerializerClass(): string
    {
        return CreatedByFieldSerializer::class;
    }
}
