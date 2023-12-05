<?php
declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Write;

use SnapAdmin\Core\Framework\DataAbstractionLayer\DataAbstractionLayerException;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityDefinition;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Write\Command\WriteCommandQueue;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[Package('core')]
class WriteParameterBag
{
    private ?string $currentWriteLanguageId = null;

    public function __construct(
        /** Defines the entity definition where the field placed in */
        private readonly EntityDefinition $definition,
        /** Contains the write context instance of the current write process */
        private readonly WriteContext $context,
        /** Contains the current property path for the proccessed field e.g product/{id}/name */
        private string $path,
        /** Contains all already applied write commands of the current write process */
        private readonly WriteCommandQueue $commandQueue,
        private PrimaryKeyBag $primaryKeyBag = new PrimaryKeyBag()
    ) {
    }

    public function getDefinition(): EntityDefinition
    {
        return $this->definition;
    }

    public function getContext(): WriteContext
    {
        return $this->context;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getCommandQueue(): WriteCommandQueue
    {
        return $this->commandQueue;
    }

    public function cloneForSubresource(EntityDefinition $definition, string $path): self
    {
        return new self(
            $definition,
            $this->context,
            $path,
            $this->commandQueue,
            $this->primaryKeyBag
        );
    }

    public function getCurrentWriteLanguageId(): string
    {
        if ($this->currentWriteLanguageId !== null) {
            return $this->currentWriteLanguageId;
        }

        return $this->context->getContext()->getLanguageId();
    }

    public function setCurrentWriteLanguageId(string $languageId): void
    {
        if (!Uuid::isValid($languageId)) {
            throw DataAbstractionLayerException::invalidLanguageId($languageId);
        }

        $this->currentWriteLanguageId = $languageId;
    }

    public function getPrimaryKeyBag(): PrimaryKeyBag
    {
        return $this->primaryKeyBag;
    }

    public function setPrimaryKeyBag(PrimaryKeyBag $primaryKeyBag): void
    {
        $this->primaryKeyBag = $primaryKeyBag;
    }
}
