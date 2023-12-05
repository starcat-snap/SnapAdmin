<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Uuid;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use SnapAdmin\Core\Framework\Uuid\Exception\InvalidUuidException;
use SnapAdmin\Core\Framework\Uuid\Exception\InvalidUuidLengthException;

#[Package('core')]
class UuidException extends HttpException
{
    public static function invalidUuid(string $uuid): SnapAdminHttpException
    {
        return new InvalidUuidException($uuid);
    }

    public static function invalidUuidLength(int $length, string $hex): SnapAdminHttpException
    {
        return new InvalidUuidLengthException($length, $hex);
    }
}
