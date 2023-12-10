<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Cms;

use SnapAdmin\Core\Framework\Log\Package;


class VimeoVideoCmsElementResolver extends YoutubeVideoCmsElementResolver
{
    public function getType(): string
    {
        return 'vimeo-video';
    }
}
