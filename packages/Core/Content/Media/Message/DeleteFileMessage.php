<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Message;

use League\Flysystem\Visibility;
use SnapAdmin\Core\Framework\MessageQueue\AsyncMessageInterface;


class DeleteFileMessage implements AsyncMessageInterface
{
    public function __construct(
        private array $files = [],
        private string $visibility = Visibility::PUBLIC
    ) {
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): void
    {
        $this->files = $files;
    }

    public function getVisibility(): string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): void
    {
        $this->visibility = $visibility;
    }
}
