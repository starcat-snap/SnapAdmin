<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Adapter\Kernel;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Routing\RequestTransformerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel as SymfonyHttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
#[Package('core')]
class HttpKernel extends SymfonyHttpKernel
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        ControllerResolverInterface $resolver,
        RequestStack $requestStack,
        ArgumentResolverInterface $argumentResolver,
        private readonly RequestTransformerInterface $requestTransformer
    ) {
        parent::__construct($dispatcher, $resolver, $requestStack, $argumentResolver);
    }

    public function handle(Request $request, int $type = HttpKernelInterface::MAIN_REQUEST, bool $catch = true): Response
    {
        if ($request->attributes->get('exception') !== null) {
            return parent::handle($request, $type, $catch);
        }

        $request = $this->requestTransformer->transform($request);

        return parent::handle($request, $type, $catch);
    }
}
