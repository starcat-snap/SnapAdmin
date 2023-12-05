<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Routing;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Validation\DataBag\QueryDataBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[Package('core')]
class QueryDataBagResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        if ($argument->getType() !== QueryDataBag::class) {
            return;
        }

        yield new QueryDataBag($request->query->all());
    }
}
