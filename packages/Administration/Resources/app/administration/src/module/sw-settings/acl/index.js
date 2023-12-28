/**
 * @package services-settings
 */
SnapAdmin.Service('privileges')
    .addPrivilegeMappingEntry({
        category: 'additional_permissions',
        parent: null,
        key: 'system',
        roles: {
            system_config: {
                privileges: [
                    'system_config:read',
                    'system_config:update',
                    'system_config:create',
                    'system_config:delete',
                    'custom_field:read',
                    'custom_field_set_relation:read',
                ],
                dependencies: [],
            },
        },
    });
