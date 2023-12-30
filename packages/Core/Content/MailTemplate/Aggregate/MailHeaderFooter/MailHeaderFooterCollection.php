<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailHeaderFooter;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<MailHeaderFooterEntity>
 */
#[Package('services-settings')]
class MailHeaderFooterCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'mail_template_header_footer_collection';
    }

    protected function getExpectedClass(): string
    {
        return MailHeaderFooterEntity::class;
    }
}
