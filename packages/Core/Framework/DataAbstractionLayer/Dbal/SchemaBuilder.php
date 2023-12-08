<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Types;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\AssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\AutoIncrementField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BlobField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BoolField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\BreadcrumbField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CalculatedPriceField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CashRoundingConfigField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ChildCountField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ChildrenAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ConfigJsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CreatedByField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CronIntervalField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\CustomFields;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateIntervalField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\EmailField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Field;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\RestrictDelete;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\Flag\Runtime;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\FloatField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\IntField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\JsonField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ListField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LockedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToManyIdField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ObjectField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToManyAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentAssociationField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ParentFkField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\PasswordField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\ReferenceVersionField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\RemoteAddressField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StorageAware;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\StringField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TimeZoneField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TranslatedField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TreeBreadcrumbField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TreeLevelField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\TreePathField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\UpdatedByField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\VariantListingConfigField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\VersionDataPayloadField;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Field\VersionField;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @internal
 */
#[Package('core')]
class SchemaBuilder
{
    /**
     * @var array<string, string>
     */
    public static array $fieldMapping = [
        IdField::class => Types::BINARY,
        FkField::class => Types::BINARY,
        ParentFkField::class => Types::BINARY,
        VersionField::class => Types::BINARY,
        ReferenceVersionField::class => Types::BINARY,
        CreatedByField::class => Types::BINARY,
        UpdatedByField::class => Types::BINARY,

        CreatedAtField::class => Types::DATETIME_MUTABLE,
        UpdatedAtField::class => Types::DATETIME_MUTABLE,
        DateTimeField::class => Types::DATETIME_MUTABLE,

        DateField::class => Types::DATE_MUTABLE,

        JsonField::class => Types::JSON,
        ListField::class => Types::JSON,
        ConfigJsonField::class => Types::JSON,
        CustomFields::class => Types::JSON,
        BreadcrumbField::class => Types::JSON,
        CashRoundingConfigField::class => Types::JSON,
        ObjectField::class => Types::JSON,
        TreeBreadcrumbField::class => Types::JSON,
        VariantListingConfigField::class => Types::JSON,
        VersionDataPayloadField::class => Types::JSON,
        ManyToManyIdField::class => Types::JSON,

        ChildCountField::class => Types::INTEGER,
        IntField::class => Types::INTEGER,
        AutoIncrementField::class => Types::INTEGER,
        TreeLevelField::class => Types::INTEGER,

        BoolField::class => Types::BOOLEAN,
        LockedField::class => Types::BOOLEAN,

        PasswordField::class => Types::STRING,
        StringField::class => Types::STRING,
        TimeZoneField::class => Types::STRING,
        CronIntervalField::class => Types::STRING,
        DateIntervalField::class => Types::STRING,
        EmailField::class => Types::STRING,
        RemoteAddressField::class => Types::STRING,

        BlobField::class => Types::BLOB,

        FloatField::class => Types::DECIMAL,

        TreePathField::class => Types::TEXT,
        LongTextField::class => Types::TEXT,
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    public static array $options = [
        Types::BINARY => [
            'length' => 16,
            'fixed' => true,
        ],

        Types::BOOLEAN => [
            'default' => 0,
        ],
    ];

    public function buildSchemaOfDefinition(EntityDefinition $definition): Table
    {
        $table = (new Schema())->createTable($definition->getEntityName());

        /** @var Field $field */
        foreach ($definition->getFields() as $field) {
            if ($field->is(Runtime::class)) {
                continue;
            }

            if ($field instanceof AssociationField) {
                continue;
            }

            if (!$field instanceof StorageAware) {
                continue;
            }

            if ($field instanceof TranslatedField) {
                continue;
            }

            $fieldType = $this->getFieldType($field);

            $table->addColumn(
                $field->getStorageName(),
                $fieldType,
                $this->getFieldOptions($field, $fieldType, $definition)
            );
        }

        /** @var StorageAware[] $primaryKeys */
        $primaryKeys = $definition->getPrimaryKeys()->filter(function (Field $field) {
            return $field instanceof StorageAware;
        })->getElements();

        $table->setPrimaryKey(array_map(function (StorageAware $field) {
            return $field->getStorageName();
        }, $primaryKeys));

        $this->addForeignKeys($table, $definition);

        return $table;
    }

    private function getFieldType(Field $field): string
    {
        foreach (self::$fieldMapping as $class => $type) {
            if ($field instanceof $class) {
                return self::$fieldMapping[$field::class];
            }
        }

        throw DataAbstractionLayerException::fieldHasNoType($field->getPropertyName());
    }

    /**
     * @return array<string, mixed>
     */
    private function getFieldOptions(Field $field, string $type, EntityDefinition $definition): array
    {
        $options = self::$options[$type] ?? [];

        $options['notnull'] = false;

        if ($field->is(Required::class) && !$field instanceof UpdatedAtField && !$field instanceof ReferenceVersionField) {
            $options['notnull'] = true;
        }

        if (\array_key_exists($field->getPropertyName(), $definition->getDefaults())) {
            $options['default'] = $definition->getDefaults()[$field->getPropertyName()];
        }

        if ($field instanceof StringField) {
            $options['length'] = $field->getMaxLength();
        }

        if ($field instanceof AutoIncrementField) {
            $options['autoincrement'] = true;
            $options['notnull'] = true;
        }

        if ($field instanceof Floatfield) {
            $options['precision'] = 10;
            $options['scale'] = 2;
        }

        return $options;
    }

    private function addForeignKeys(Table $table, EntityDefinition $definition): void
    {
        $fields = $definition->getFields()->filter(
            function (Field $field) {
                if ($field instanceof ManyToOneAssociationField
                    || ($field instanceof OneToOneAssociationField && $field->getStorageName() !== 'id')) {
                    return true;
                }

                return false;
            }
        );

        $referenceVersionFields = $definition->getFields()->filterInstance(ReferenceVersionField::class);

        /** @var ManyToOneAssociationField $field */
        foreach ($fields as $field) {
            $reference = $field->getReferenceDefinition();

            $hasOneToMany = $definition->getFields()->filter(function (Field $field) use ($reference) {
                    if (!$field instanceof OneToManyAssociationField) {
                        return false;
                    }
                    if ($field instanceof ChildrenAssociationField) {
                        return false;
                    }

                    return $field->getReferenceDefinition() === $reference;
                })->count() > 0;

            // skip foreign key to prevent bi-directional foreign key
            if ($hasOneToMany) {
                continue;
            }

            $columns = [
                $field->getStorageName(),
            ];

            $referenceColumns = [
                $field->getReferenceField(),
            ];

            if ($reference->isVersionAware()) {
                $versionField = null;

                /** @var ReferenceVersionField $referenceVersionField */
                foreach ($referenceVersionFields as $referenceVersionField) {
                    if ($referenceVersionField->getVersionReferenceDefinition() === $reference) {
                        $versionField = $referenceVersionField;

                        break;
                    }
                }

                if ($field instanceof ParentAssociationField) {
                    $columns[] = 'version_id';
                } else {
                    /** @var ReferenceVersionField $versionField */
                    $columns[] = $versionField->getStorageName();
                }

                $referenceColumns[] = 'version_id';
            }

            $update = 'CASCADE';

            if ($field->is(CascadeDelete::class)) {
                $delete = 'CASCADE';
            } elseif ($field->is(RestrictDelete::class)) {
                $delete = 'RESTRICT';
            } else {
                $delete = 'SET NULL';
            }

            $table->addForeignKeyConstraint(
                $reference->getEntityName(),
                $columns,
                $referenceColumns,
                [
                    'onUpdate' => $update,
                    'onDelete' => $delete,
                ],
                sprintf('fk.%s.%s', $definition->getEntityName(), $field->getStorageName())
            );
        }
    }
}
