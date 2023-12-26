<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\StateMachineException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class UnnecessaryTransitionException extends StateMachineException
{
    public function __construct(string $transition)
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::UNNECESSARY_TRANSITION,
            'The transition "{{ transition }}" is unnecessary, already on desired state.',
            ['transition' => $transition]
        );
    }
}
