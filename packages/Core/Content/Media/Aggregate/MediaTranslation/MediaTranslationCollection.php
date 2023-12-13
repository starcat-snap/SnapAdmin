<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Aggregate\MediaTranslation;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<MediaTranslationEntity>
 */
class MediaTranslationCollection extends EntityCollection
{
    /**
     * @return array<string>
     */
    public function getMediaIds(): array
    {
        return $this->fmap(fn (MediaTranslationEntity $mediaTranslation) => $mediaTranslation->getMediaId());
    }

    public function filterByMediaId(string $id): self
    {
        return $this->filter(fn (MediaTranslationEntity $mediaTranslation) => $mediaTranslation->getMediaId() === $id);
    }

    /**
     * @return array<string>
     */
    public function getLanguageIds(): array
    {
        return $this->fmap(fn (MediaTranslationEntity $mediaTranslation) => $mediaTranslation->getLanguageId());
    }

    public function filterByLanguageId(string $id): self
    {
        return $this->filter(fn (MediaTranslationEntity $mediaTranslation) => $mediaTranslation->getLanguageId() === $id);
    }

    public function getApiAlias(): string
    {
        return 'media_translation_collection';
    }

    protected function getExpectedClass(): string
    {
        return MediaTranslationEntity::class;
    }
}
