<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\StateMachine\Api;

use SnapAdmin\Core\Framework\Api\Response\ResponseFactoryInterface;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\DefinitionInstanceRegistry;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineState\StateMachineStateDefinition;
use SnapAdmin\Core\System\StateMachine\Aggregation\StateMachineTransition\StateMachineTransitionEntity;
use SnapAdmin\Core\System\StateMachine\StateMachineRegistry;
use SnapAdmin\Core\System\StateMachine\Transition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
#[Package('system-settings')]
class StateMachineActionController extends AbstractController
{
    /**
     * @var StateMachineRegistry
     */
    protected $stateMachineRegistry;

    /**
     * @internal
     */
    public function __construct(
        StateMachineRegistry $stateMachineRegistry,
        private readonly DefinitionInstanceRegistry $definitionInstanceRegistry
    ) {
        $this->stateMachineRegistry = $stateMachineRegistry;
    }

    #[Route(path: '/api/_action/state-machine/{entityName}/{entityId}/state', name: 'api.state_machine.states', methods: ['GET'])]
    public function getAvailableTransitions(
        Request $request,
        Context $context,
        string $entityName,
        string $entityId
    ): JsonResponse {
        $stateFieldName = (string) $request->query->get('stateFieldName', 'stateId');

        $availableTransitions = $this->stateMachineRegistry->getAvailableTransitions(
            $entityName,
            $entityId,
            $stateFieldName,
            $context
        );

        $transitionsJson = [];
        /** @var StateMachineTransitionEntity $transition */
        foreach ($availableTransitions as $transition) {
            $transitionsJson[] = [
                'name' => $transition->getToStateMachineState()->getName(),
                'technicalName' => $transition->getToStateMachineState()->getTechnicalName(),
                'actionName' => $transition->getActionName(),
                'fromStateName' => $transition->getFromStateMachineState()->getTechnicalName(),
                'toStateName' => $transition->getToStateMachineState()->getTechnicalName(),
                'url' => $this->generateUrl('api.state_machine.transition_state', [
                    'entityName' => $entityName,
                    'entityId' => $entityId,
                    'version' => $request->attributes->get('version'),
                    'transition' => $transition->getActionName(),
                ]),
            ];
        }

        return new JsonResponse([
            'transitions' => $transitionsJson,
        ]);
    }

    #[Route(path: '/api/_action/state-machine/{entityName}/{entityId}/state/{transition}', name: 'api.state_machine.transition_state', methods: ['POST'])]
    public function transitionState(
        Request $request,
        Context $context,
        ResponseFactoryInterface $responseFactory,
        string $entityName,
        string $entityId,
        string $transition
    ): Response {
        $stateFieldName = (string) $request->query->get('stateFieldName', 'stateId');
        $stateMachineStateCollection = $this->stateMachineRegistry->transition(
            new Transition(
                $entityName,
                $entityId,
                $transition,
                $stateFieldName
            ),
            $context
        );

        return $responseFactory->createDetailResponse(
            new Criteria(),
            $stateMachineStateCollection->get('toPlace'),
            $this->definitionInstanceRegistry->get(StateMachineStateDefinition::class),
            $request,
            $context
        );
    }
}
