<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\Snippet\Filter;

use SnapAdmin\Core\Framework\Log\Package;

#[Package('system-settings')]
class EditedFilter extends AbstractFilter implements SnippetFilterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'edited';
    }

    /**
     * {@inheritdoc}
     */
    public function filter(array $snippets, $requestFilterValue): array
    {
        $result = [];
        foreach ($snippets as $setId => $set) {
            foreach ($set['snippets'] as $translationKey => $snippet) {
                if ($snippet['id'] === null || mb_strpos((string) $snippet['author'], 'user/') === 0) {
                    continue;
                }

                $result[$setId]['snippets'][$translationKey] = $snippet;
            }
        }

        return $this->readjust($result, $snippets);
    }
}
