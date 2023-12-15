<?php declare(strict_types=1);

namespace SnapAdmin\Core\Test\Stub\DataAbstractionLayer;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder\DefaultFieldAccessorBuilder;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Dbal\FieldAccessorBuilder\FieldAccessorBuilderInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\BlobFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\BoolFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\CreatedAtFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\FieldSerializerInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\FkFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\FloatFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\IdFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\IntFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\JsonFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\ManyToManyAssociationFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\ManyToOneAssociationFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\OneToManyAssociationFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\OneToOneAssociationFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\StringFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\FieldSerializer\UpdatedAtFieldSerializer;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\EntityWriteGatewayInterface;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\WriteCommandExtractor;
use SnapAdmin\Core\Framework\Util\HtmlSanitizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @final
 */
class StaticDefinitionInstanceRegistry extends DefinitionInstanceRegistry
{
    /**
     * @var FieldSerializerInterface[]
     */
    private array $serializers;

    /**
     * @param array<int|string, class-string<EntityDefinition>|EntityDefinition> $registeredDefinitions
     */
    public function __construct(
        array $registeredDefinitions,
        private readonly ValidatorInterface $validator,
        private readonly EntityWriteGatewayInterface $entityWriteGateway
    ) {
        parent::__construct(new ContainerBuilder(), [], []);

        $this->setUpSerializers();

        foreach ($registeredDefinitions as $serviceId => $definition) {
            $this->register(
                $definition instanceof EntityDefinition ? $definition : new $definition(),
                \is_string($serviceId) ? $serviceId : null
            );
        }
    }

    public function getSerializer(string $serializerClass): FieldSerializerInterface
    {
        return $this->serializers[$serializerClass];
    }

    public function getAccessorBuilder(string $accessorBuilderClass): FieldAccessorBuilderInterface
    {
        return new DefaultFieldAccessorBuilder();
    }

    private function setUpSerializers(): void
    {
        $this->serializers = [
            IdFieldSerializer::class => new IdFieldSerializer($this->validator, $this),
            FkFieldSerializer::class => new FkFieldSerializer($this->validator, $this),
            StringFieldSerializer::class => new StringFieldSerializer($this->validator, $this, new HtmlSanitizer()),
            IntFieldSerializer::class => new IntFieldSerializer($this->validator, $this),
            FloatFieldSerializer::class => new FloatFieldSerializer($this->validator, $this),
            BoolFieldSerializer::class => new BoolFieldSerializer($this->validator, $this),
            JsonFieldSerializer::class => new JsonFieldSerializer($this->validator, $this),
            CreatedAtFieldSerializer::class => new CreatedAtFieldSerializer($this->validator, $this),
            UpdatedAtFieldSerializer::class => new UpdatedAtFieldSerializer($this->validator, $this),
            BlobFieldSerializer::class => new BlobFieldSerializer(),
            ManyToManyAssociationFieldSerializer::class => new ManyToManyAssociationFieldSerializer(
                new WriteCommandExtractor($this->entityWriteGateway),
            ),
            ManyToOneAssociationFieldSerializer::class => new ManyToOneAssociationFieldSerializer(
                new WriteCommandExtractor($this->entityWriteGateway),
            ),
            OneToManyAssociationFieldSerializer::class => new OneToManyAssociationFieldSerializer(
                new WriteCommandExtractor($this->entityWriteGateway),
            ),
            OneToOneAssociationFieldSerializer::class => new OneToOneAssociationFieldSerializer(
                new WriteCommandExtractor($this->entityWriteGateway),
            ),
        ];
    }
}
