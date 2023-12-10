<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Message;

use SnapAdmin\Core\Framework\Log\Package;


class UpdateThumbnailsMessage extends GenerateThumbnailsMessage
{
    private bool $isStrict = false;

    public function isStrict(): bool
    {
        return $this->isStrict;
    }

    public function setIsStrict(bool $isStrict): void
    {
        $this->isStrict = $isStrict;
    }
}
