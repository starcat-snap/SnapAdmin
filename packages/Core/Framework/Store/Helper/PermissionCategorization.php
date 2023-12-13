<?php declare(strict_types=1);

namespace SnapAdmin\Core\Framework\Store\Helper;

use SnapAdmin\Core\Framework\DataAbstractionLayer\Version\VersionDefinition;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSet\CustomFieldSetDefinition;
use SnapAdmin\Core\System\CustomField\Aggregate\CustomFieldSetRelation\CustomFieldSetRelationDefinition;
use SnapAdmin\Core\System\CustomField\CustomFieldDefinition;
use SnapAdmin\Core\System\Language\LanguageDefinition;
use SnapAdmin\Core\System\Locale\Aggregate\LocaleTranslation\LocaleTranslationDefinition;
use SnapAdmin\Core\System\Locale\LocaleDefinition;
use SnapAdmin\Core\System\SystemConfig\SystemConfigDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserAccessKey\UserAccessKeyDefinition;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryDefinition;
use SnapAdmin\Core\System\User\UserDefinition;

/**
 * @internal
 */
#[Package('services-settings')]
class PermissionCategorization
{
    private const CATEGORY_ADMIN_USER = 'admin_user';
    private const CATEGORY_CUSTOM_FIELDS = 'custom_fields';
    private const CATEGORY_OTHER = 'other';
    private const CATEGORY_SETTINGS = 'settings';
    private const CATEGORY_ADDITIONAL_PRIVILEGES = 'additional_privileges';

    private const PERMISSION_CATEGORIES = [
        self::CATEGORY_ADMIN_USER => [
            UserDefinition::ENTITY_NAME,
            UserAccessKeyDefinition::ENTITY_NAME,
            UserRecoveryDefinition::ENTITY_NAME,
        ],

        self::CATEGORY_CUSTOM_FIELDS => [
            CustomFieldDefinition::ENTITY_NAME,
            CustomFieldSetDefinition::ENTITY_NAME,
            CustomFieldSetRelationDefinition::ENTITY_NAME,
        ],

        self::CATEGORY_SETTINGS => [
            LanguageDefinition::ENTITY_NAME,
            LocaleDefinition::ENTITY_NAME,
            LocaleTranslationDefinition::ENTITY_NAME,
            SystemConfigDefinition::ENTITY_NAME,
            VersionDefinition::ENTITY_NAME,
        ],

        self::CATEGORY_ADDITIONAL_PRIVILEGES => [
            'additional_privileges',
        ],
    ];

    public static function isInCategory(string $entity, string $category): bool
    {
        if ($category === self::CATEGORY_OTHER) {
            $allCategories = array_merge(...array_values(self::PERMISSION_CATEGORIES));

            return !\in_array($entity, $allCategories, true);
        }

        return \in_array($entity, self::PERMISSION_CATEGORIES[$category], true);
    }

    /**
     * @return string[]
     */
    public static function getCategoryNames(): array
    {
        $categories = array_keys(self::PERMISSION_CATEGORIES);
        $categories[] = self::CATEGORY_OTHER;

        return $categories;
    }
}
