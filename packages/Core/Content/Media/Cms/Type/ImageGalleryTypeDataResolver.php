<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Cms\Type;

use SnapAdmin\Core\Framework\Log\Package;


class ImageGalleryTypeDataResolver extends ImageSliderTypeDataResolver
{
    public function getType(): string
    {
        return 'image-gallery';
    }
}
