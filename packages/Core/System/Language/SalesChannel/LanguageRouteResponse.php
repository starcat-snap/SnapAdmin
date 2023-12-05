<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Language\SalesChannel;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Language\LanguageCollection;
use SnapAdmin\Core\System\SalesChannel\StoreApiResponse;

#[Package('buyers-experience')]
class LanguageRouteResponse extends StoreApiResponse
{
    /**
     * @var EntitySearchResult<LanguageCollection>
     */
    protected $object;

    /**
     * @param EntitySearchResult<LanguageCollection> $languages
     */
    public function __construct(EntitySearchResult $languages)
    {
        parent::__construct($languages);
    }

    public function getLanguages(): LanguageCollection
    {
        return $this->object->getEntities();
    }
}
