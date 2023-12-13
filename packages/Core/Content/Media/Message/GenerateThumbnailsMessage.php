<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Message;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\MessageQueue\AsyncMessageInterface;

class GenerateThumbnailsMessage implements AsyncMessageInterface
{
    /**
     * @var array<string>
     */
    private array $mediaIds = [];

    private Context $context;

    /**
     * @return array<string>
     */
    public function getMediaIds(): array
    {
        return $this->mediaIds;
    }

    /**
     * @param array<string> $mediaIds
     */
    public function setMediaIds(array $mediaIds): void
    {
        $this->mediaIds = $mediaIds;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }
}
