<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Changelog;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
enum ChangelogSection: string
{
    case core = 'Core';
    case api = 'API';
    case administration = 'Administration';
    case storefront = 'Storefront';
    case upgrade = 'Upgrade Information';
    case major = 'Next Major Version Changes';
}
