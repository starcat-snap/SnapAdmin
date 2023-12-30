<?php declare(strict_types=1);

namespace SnapAdmin\Core\Migration\V6_6;

use Doctrine\DBAL\Connection;
use SnapAdmin\Core\Content\MailTemplate\MailTemplateTypes;
use SnapAdmin\Core\Defaults;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\Framework\Migration\MigrationStep;
use SnapAdmin\Core\Framework\Uuid\Uuid;

/**
 * @internal
 */
#[Package('core')]
class Migration1703957524UserPasswordRecovery extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1703957524;
    }

    public function update(Connection $connection): void
    {
        $mailTemplateTypeId = $this->createMailTemplateType($connection);

        $this->createMailTemplate($connection, $mailTemplateTypeId);
    }

    private function getLanguageIdByLocale(Connection $connection, string $locale): ?string
    {
        $sql = <<<'SQL'
SELECT `language`.`id`
FROM `language`
INNER JOIN `locale` ON `locale`.`id` = `language`.`locale_id`
WHERE `locale`.`code` = :code
SQL;

        $languageId = $connection->executeQuery($sql, ['code' => $locale])->fetchOne();
        if (!$languageId && $locale !== 'zh-CN') {
            return null;
        }

        if (!$languageId) {
            return Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM);
        }

        return $languageId;
    }

    private function createMailTemplateType(Connection $connection): string
    {
        $mailTemplateTypeId = Uuid::randomHex();

        $connection->insert('mail_template_type', [
            'id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'technical_name' => MailTemplateTypes::MAILTYPE_USER_RECOVERY_REQUEST,
            'available_entities' => json_encode(['userRecovery' => 'user_recovery']),
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);


        $connection->insert('mail_template_type_translation', [
            'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
            'name' => 'User password recovery',
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        return $mailTemplateTypeId;
    }

    private function createMailTemplate(Connection $connection, string $mailTemplateTypeId): void
    {
        $mailTemplateId = Uuid::randomHex();

        $defaultLangId = $this->getLanguageIdByLocale($connection, 'zh-CN');

        $connection->insert('mail_template', [
            'id' => Uuid::fromHexToBytes($mailTemplateId),
            'mail_template_type_id' => Uuid::fromHexToBytes($mailTemplateTypeId),
            'system_default' => true,
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);

        $connection->insert('mail_template_translation', [
            'mail_template_id' => Uuid::fromHexToBytes($mailTemplateId),
            'language_id' => Uuid::fromHexToBytes(Defaults::LANGUAGE_SYSTEM),
            'sender_name' => 'Snap Administration',
            'subject' => '密码恢复',
            'description' => '',
            'content_html' => $this->getContentHtmlZh(),
            'content_plain' => $this->getContentPlainZh(),
            'created_at' => (new \DateTime())->format(Defaults::STORAGE_DATE_TIME_FORMAT),
        ]);
    }

    private function getContentHtmlZh(): string
    {
        return <<<MAIL
<div style="font-family:arial; font-size:12px;">
    <p>
        尊敬的用户 {{ userRecovery.user.username }} 您好: <br/>
        <br/>
        这是您重置密码的请求，请确认下面的链接以指定新密码.<br/>
        <br/>
        <a href="{{ resetUrl }}">重置密码</a><br/>
        <br/>
        此链接在接下来的2小时内有效。之后，您必须请求一个新的确认链接.<br/>
        如果您不想重置密码，请忽略此邮件，不会有任何改变。
    </p>
</div>
MAIL;
    }

    private function getContentPlainZh(): string
    {
        return <<<MAIL
        尊敬的用户 {{ userRecovery.user.username }} 您好:

        这是您重置密码的请求，请确认下面的链接以指定新密码.

        重置密码: {{ resetUrl }}

        此链接在接下来的2小时内有效。之后，您必须请求一个新的确认链接.
        如果您不想重置密码，请忽略此邮件，不会有任何改变。
MAIL;
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
