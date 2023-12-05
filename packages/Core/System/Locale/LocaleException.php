<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Locale;

use SnapAdmin\Core\Framework\Feature;
use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\Exception\LanguageNotFoundException;
use Symfony\Component\HttpFoundation\Response;

#[Package('buyers-experience')]
class LocaleException extends HttpException
{
    final public const LOCALE_DOES_NOT_EXISTS_EXCEPTION = 'SYSTEM__LOCALE_DOES_NOT_EXISTS';
    final public const LANGUAGE_NOT_FOUND = 'SYSTEM__LANGUAGE_NOT_FOUND';

    public static function localeDoesNotExists(string $locale): self
    {
        return new self(
            Response::HTTP_NOT_FOUND,
            self::LOCALE_DOES_NOT_EXISTS_EXCEPTION,
            'The locale {{ locale }} does not exists.',
            ['locale' => $locale]
        );
    }

    /**
     * @deprecated tag:v6.6.0 - reason:return-type-change - will return `self` in the future
     */
    public static function languageNotFound(?string $languageId): HttpException
    {
        if (!Feature::isActive('v6.6.0.0')) {
            return new LanguageNotFoundException($languageId);
        }

        return new self(
            Response::HTTP_PRECONDITION_FAILED,
            self::LANGUAGE_NOT_FOUND,
            self::$couldNotFindMessage,
            ['entity' => 'language', 'field' => 'id', 'value' => $languageId]
        );
    }
}
