<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Api\Context;

use SnapAdmin\Core\Framework\Context;
use SnapAdmin\Core\Framework\Log\Package;

#[Package('core')]
class AdminChannelApiSource extends ChannelApiSource
{
    public string $type = 'admin-channel-api';

    /**
     * @var Context
     */
    protected $originalContext;

    public function __construct(
        string  $channelId,
        Context $originalContext
    )
    {
        parent::__construct($channelId);

        $this->originalContext = $originalContext;
    }

    public function getOriginalContext(): Context
    {
        return $this->originalContext;
    }
}
