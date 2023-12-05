<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Field;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\VersionFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Version\VersionDefinition;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class VersionField extends FkField
{
    public function __construct()
    {
        parent::__construct('version_id', 'versionId', VersionDefinition::class);

        $this->addFlags(new PrimaryKey(), new Required());
    }

    protected function getSerializerClass(): string
    {
        return VersionFieldSerializer::class;
    }
}
