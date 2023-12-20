/**
 * @package admin
 */

/**
 * @private
 */
SnapAdmin.Filter.register('fileSize', (value: number, locale: string) => {
    if (!value) {
        return '';
    }

    return SnapAdmin.Utils.format.fileSize(value, locale);
});

/* @private */
export {};
