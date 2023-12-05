<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\DataAbstractionLayer;

use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Language\LanguageEntity;

#[Package('core')]
class TranslationEntity extends Entity
{
    /**
     * @var string
     */
    protected $languageId;

    /**
     * @var LanguageEntity|null
     */
    protected $language;

    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function setLanguageId(string $languageId): void
    {
        $this->languageId = $languageId;
    }

    public function getLanguage(): ?LanguageEntity
    {
        return $this->language;
    }

    public function setLanguage(LanguageEntity $language): void
    {
        $this->language = $language;
    }
}
