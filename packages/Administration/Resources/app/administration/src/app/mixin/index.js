/**
 * @package admin
 */

// eslint-disable-next-line sw-deprecation-rules/private-feature-declarations
export default () => {
    const context = require.context('./', true, /(?<!index)\.(?<!spec\.)(?<!spec\.vue2\.)(js|ts)$/);
    return context.keys().forEach(item => context(item));
};
