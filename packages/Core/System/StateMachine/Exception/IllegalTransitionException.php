<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Exception;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\StateMachineException;
use Symfony\Component\HttpFoundation\Response;

#[Package('system-settings')]
class IllegalTransitionException extends StateMachineException
{
    /**
     * @param array<mixed> $possibleTransitions
     */
    public function __construct(
        string $currentState,
        string $transition,
        array $possibleTransitions
    ) {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            self::ILLEGAL_STATE_TRANSITION,
            'Illegal transition "{{ transition }}" from state "{{ currentState }}". Possible transitions are: {{ possibleTransitionsString }}',
            [
                'transition' => $transition,
                'currentState' => $currentState,
                'possibleTransitionsString' => implode(', ', $possibleTransitions),
                'possibleTransitions' => $possibleTransitions,
            ]
        );
    }
}
