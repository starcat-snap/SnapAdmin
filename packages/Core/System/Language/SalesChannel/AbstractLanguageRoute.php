<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\SalesChannel;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * This route can be used to load all currencies of the authenticated sales channel.
 * With this route it is also possible to send the standard API parameters such as: 'page', 'limit', 'filter', etc.
 */
#[Package('buyers-experience')]
abstract class AbstractLanguageRoute
{
    abstract public function getDecorated(): AbstractLanguageRoute;

    abstract public function load(Request $request, SalesChannelContext $context, Criteria $criteria): LanguageRouteResponse;
}
