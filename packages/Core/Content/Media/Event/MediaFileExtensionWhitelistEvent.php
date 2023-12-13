<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\Media\Event;

use Symfony\Contracts\EventDispatcher\Event;

class MediaFileExtensionWhitelistEvent extends Event
{
    /**
     * @param array<string> $whitelist
     */
    public function __construct(private array $whitelist)
    {
    }

    /**
     * @return array<string>
     */
    public function getWhitelist()
    {
        return $this->whitelist;
    }

    /**
     * @param array<string> $whitelist
     */
    public function setWhitelist(array $whitelist): void
    {
        $this->whitelist = $whitelist;
    }
}
