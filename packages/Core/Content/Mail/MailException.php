<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Mail;

use SnapAdmin\Core\Framework\HttpException;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\SnapAdminHttpException;
use Symfony\Component\HttpFoundation\Response;

#[Package('services-settings')]
class MailException extends HttpException
{
    final public const GIVEN_OPTION_INVALID = 'MAIL__GIVEN_OPTION_INVALID';

    final public const GIVEN_AGENT_INVALID = 'MAIL__GIVEN_AGENT_INVALID';

    /**
     * @param string[] $validOptions
     */
    public static function givenSendMailOptionIsInvalid(string $option, array $validOptions): SnapAdminHttpException
    {
        return new self(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            self::GIVEN_OPTION_INVALID,
            'Given sendmail option "{{ option }}" is invalid. Available options: {{ validOptions }}',
            ['option' => $option, 'validOptions' => implode(', ', $validOptions)]
        );
    }

    public static function givenMailAgentIsInvalid(string $agent): SnapAdminHttpException
    {
        return new self(
            Response::HTTP_INTERNAL_SERVER_ERROR,
            self::GIVEN_AGENT_INVALID,
            'Invalid mail agent given "{{ agent }}"',
            ['agent' => $agent]
        );
    }
}
