<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer\Version;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Version\Aggregate\VersionCommit\VersionCommitCollection;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class VersionEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var VersionCommitCollection
     */
    protected $commits;

    public function __construct()
    {
        $this->commits = new VersionCommitCollection();
    }

    public function getCommits(): VersionCommitCollection
    {
        return $this->commits;
    }

    public function setCommits(VersionCommitCollection $commits): void
    {
        $this->commits = $commits;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
