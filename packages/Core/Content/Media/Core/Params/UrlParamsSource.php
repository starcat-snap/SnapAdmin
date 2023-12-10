<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Core\Params;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('content')]
enum UrlParamsSource
{
    case MEDIA;
    case THUMBNAIL;
}
