<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework;

use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('core')]
class FrameworkException extends HttpException
{
    private const PROJECT_DIR_NOT_EXISTS = 'FRAMEWORK__PROJECT_DIR_NOT_EXISTS';

    public static function projectDirNotExists(string $dir, ?\Throwable $e = null): self
    {
        return new self(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            'Project directory "{{ dir }}" does not exist.',
            self::PROJECT_DIR_NOT_EXISTS,
            ['dir' => $dir],
            $e
        );
    }
}
