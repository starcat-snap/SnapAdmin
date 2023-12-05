<?php declare(strict_types=1);

namespace SnapAdmin\Core\Maintenance\System\Exception;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class JwtCertificateGenerationException extends \RuntimeException
{
}
