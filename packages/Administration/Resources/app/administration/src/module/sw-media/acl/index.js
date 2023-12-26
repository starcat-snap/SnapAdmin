/**
 * @package buyers-experience
 */
SnapAdmin.Service('privileges')
    .addPrivilegeMappingEntry({
        category: 'permissions',
        parent: 'system',
        key: 'media',
        roles: {
            viewer: {
                privileges: [
                    'media:read',
                    'media_folder:read',
                    'media_default_folder:read',
                    'media_thumbnail_size:read',
                    'media_folder_configuration:read',
                ],
                dependencies: [],
            },
            editor: {
                privileges: [
                    'media:update',
                    'media_folder:update',
                    'media_default_folder:update',
                    'media_folder_configuration:create',
                    'media_folder_configuration:update',
                    'media_folder_configuration:delete',
                    'media_folder:delete',
                    'media_thumbnail_size:create',
                    'media_thumbnail_size:update',
                    'media_thumbnail_size:delete',
                    'media_folder_configuration_media_thumbnail_size:delete',
                    'media_folder_configuration_media_thumbnail_size:create',
                    'media_folder_configuration_media_thumbnail_size:update',
                ],
                dependencies: [
                    'media.viewer',
                ],
            },
            creator: {
                privileges: [
                    'media:create',
                    'media_folder:create',
                    'media_default_folder:create',
                ],
                dependencies: [
                    'media.viewer',
                    'media.editor',
                ],
            },
            deleter: {
                privileges: [
                    'media:delete',
                    'media_folder:delete',
                    'media_default_folder:delete',
                ],
                dependencies: [
                    'media.viewer',
                ],
            },
        },
    });
