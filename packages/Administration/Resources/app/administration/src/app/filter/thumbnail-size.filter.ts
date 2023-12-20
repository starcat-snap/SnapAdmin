/**
 * @package content
 */
import type { Entity } from '@snap-admin/admin-extension-sdk/es/data/_internals/Entity';

SnapAdmin.Filter.register('thumbnailSize', (value: Entity<'media_thumbnail_size'>) => {
    if (!value || !(value.getEntityName() === 'media_thumbnail_size')) {
        return '';
    }

    if ((!value.width) || (!value.height)) {
        return '';
    }

    return `${value.width}x${value.height}`;
});

/* @private */
export {};
