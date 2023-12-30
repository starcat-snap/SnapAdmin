<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<MailTemplateEntity>
 */
#[Package('services-settings')]
class MailTemplateCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'mail_template_collection';
    }

    protected function getExpectedClass(): string
    {
        return MailTemplateEntity::class;
    }
}
