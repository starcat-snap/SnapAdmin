<?php declare(strict_types=1);

namespace SnapAdmin\Core\Content\MailTemplate\Aggregate\MailTemplateType;

use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCollection;
use SnapAdmin\Core\Framework\Log\Package;

/**
 * @extends EntityCollection<MailTemplateTypeEntity>
 */
#[Package('services-settings')]
class MailTemplateTypeCollection extends EntityCollection
{
    public function getApiAlias(): string
    {
        return 'mail_template_type_collection';
    }

    protected function getExpectedClass(): string
    {
        return MailTemplateTypeEntity::class;
    }
}
