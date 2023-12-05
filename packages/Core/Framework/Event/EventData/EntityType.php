<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Event\EventData;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('business-ops')]
class EntityType implements EventDataType
{
    final public const TYPE = 'entity';

    /**
     * @var class-string<EntityDefinition>
     */
    private readonly string $definitionClass;

    /**
     * @param class-string<EntityDefinition>|EntityDefinition $definitionClass
     */
    public function __construct(string|EntityDefinition $definitionClass)
    {
        if ($definitionClass instanceof EntityDefinition) {
            $definitionClass = $definitionClass::class;
        }

        $this->definitionClass = $definitionClass;
    }

    public function toArray(): array
    {
        return [
            'type' => self::TYPE,
            'entityClass' => $this->definitionClass,
        ];
    }
}
