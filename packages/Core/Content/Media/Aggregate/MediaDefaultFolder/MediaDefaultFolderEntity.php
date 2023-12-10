<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaDefaultFolder;

use SnapAdmin\Core\Content\Media\Aggregate\MediaFolder\MediaFolderEntity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\Log\Package;


class MediaDefaultFolderEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @deprecated tag:v6.6.0 Associations are now determined by `\SnapAdmin\Core\Content\Media\MediaDefinition`
     *
     * @var array<string>
     */
    protected $associationFields;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var MediaFolderEntity|null
     */
    protected $folder;

    /**
     * @deprecated tag:v6.6.0 Associations are now determined by `\SnapAdmin\Core\Content\Media\MediaDefinition`
     *
     * @return array<string>
     */
    public function getAssociationFields(): array
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0')
        );

        return $this->associationFields;
    }

    /**
     * @deprecated tag:v6.6.0 Associations are now determined by `\SnapAdmin\Core\Content\Media\MediaDefinition`
     *
     * @param array<string> $associationFields
     */
    public function setAssociationFields(array $associationFields): void
    {
        Feature::triggerDeprecationOrThrow(
            'v6.6.0.0',
            Feature::deprecatedMethodMessage(self::class, __METHOD__, 'v6.6.0.0')
        );

        $this->associationFields = $associationFields;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getFolder(): ?MediaFolderEntity
    {
        return $this->folder;
    }

    public function setFolder(?MediaFolderEntity $folder): void
    {
        $this->folder = $folder;
    }
}
