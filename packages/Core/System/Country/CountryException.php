<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Country;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use Symfony\Component\HttpFoundation\Response;

#[Package('system')]
class CountryException extends HttpException
{
    public const COUNTRY_NOT_FOUND = 'CHECKOUT__COUNTRY_NOT_FOUND';
    public const COUNTRY_STATE_NOT_FOUND = 'CHECKOUT__COUNTRY_STATE_NOT_FOUND';

    public static function countryNotFound(string $id): self
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::COUNTRY_NOT_FOUND,
            self::$couldNotFindMessage,
            ['entity' => 'country', 'field' => 'id', 'value' => $id]
        );
    }

    public static function countryStateNotFound(string $id): self
    {
        return new self(
            Response::HTTP_BAD_REQUEST,
            self::COUNTRY_STATE_NOT_FOUND,
            self::$couldNotFindMessage,
            ['entity' => 'country state', 'field' => 'id', 'value' => $id]
        );
    }
}
