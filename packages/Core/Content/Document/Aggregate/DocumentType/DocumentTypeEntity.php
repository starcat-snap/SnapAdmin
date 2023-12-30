<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Document\Aggregate\DocumentType;

use SnapAdmin\Core\Content\Document\Aggregate\DocumentBaseConfig\DocumentBaseConfigCollection;
use SnapAdmin\Core\Content\Document\DocumentCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
class DocumentTypeEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $technicalName;


    /**
     * @var DocumentCollection|null
     */
    protected $documents;

    /**
     * @var DocumentBaseConfigCollection|null
     */
    protected $documentBaseConfigs;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTechnicalName(): string
    {
        return $this->technicalName;
    }

    public function setTechnicalName(string $technicalName): void
    {
        $this->technicalName = $technicalName;
    }

    public function getDocuments(): ?DocumentCollection
    {
        return $this->documents;
    }

    public function setDocuments(DocumentCollection $documents): void
    {
        $this->documents = $documents;
    }

    public function getDocumentBaseConfigs(): ?DocumentBaseConfigCollection
    {
        return $this->documentBaseConfigs;
    }

    public function setDocumentBaseConfigs(DocumentBaseConfigCollection $documentBaseConfigs): void
    {
        $this->documentBaseConfigs = $documentBaseConfigs;
    }
}
