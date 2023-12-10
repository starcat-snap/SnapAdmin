<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\MediaType;

use SnapAdmin\Core\Framework\Log\Package;


class ImageType extends MediaType
{
    final public const ANIMATED = 'animated';
    final public const TRANSPARENT = 'transparent';
    final public const VECTOR_GRAPHIC = 'vectorGraphic';
    final public const ICON = 'image/x-icon';

    protected $name = 'IMAGE';
}
