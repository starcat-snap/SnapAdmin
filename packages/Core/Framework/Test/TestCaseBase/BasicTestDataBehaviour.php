<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Test\TestCaseBase;

use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityRepository;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Criteria;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use SnapAdmin\Core\System\Language\LanguageEntity;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait BasicTestDataBehaviour
{
    abstract protected static function getContainer(): ContainerInterface;

    protected function getSnippetSetIdForLocale(string $locale): ?string
    {
        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('snippet_set.repository');

        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('iso', $locale))
            ->setLimit(1);

        return $repository->searchIds($criteria, Context::createDefaultContext())->firstId();
    }

        protected function getLocaleIdOfSystemLanguage(): string
        {
            /** @var EntityRepository $repository */
            $repository = $this->getContainer()->get('language.repository');

            /** @var LanguageEntity $language */
            $language = $repository->search(new Criteria([Defaults::LANGUAGE_SYSTEM]), Context::createDefaultContext())->get(Defaults::LANGUAGE_SYSTEM);

            return $language->getLocaleId();
        }
}
