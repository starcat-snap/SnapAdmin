<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\DataAbstractionLayer\Field\TestDefinition;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldCollection;

/**
 * @internal
 */
class JsonDefinition extends EntityDefinition
{
    final public const ENTITY_NAME = '_test_nullable';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function since(): ?string
    {
        return '6.0.0.0';
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new Required(), new PrimaryKey()),
            (new JsonField('data', 'data'))->addFlags(new ApiAware()),
            new JsonField('root', 'root', [
                new JsonField('child', 'child', [
                    (new DateTimeField('childDateTime', 'childDateTime'))->addFlags(new ApiAware()),
                    (new DateField('childDate', 'childDate'))->addFlags(new ApiAware()),
                ]),
            ]),
        ]);
    }
}
