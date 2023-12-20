/**
 * @package admin
 */

SnapAdmin.Filter.register('date', (value: string, options: Intl.DateTimeFormatOptions = {}): string => {
    if (!value) {
        return '';
    }

    return SnapAdmin.Utils.format.date(value, options);
});

/**
 * @private
 */
export default {};
