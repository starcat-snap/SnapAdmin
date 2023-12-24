/**
 * @package admin
 */

/* eslint-disable max-len */
module.exports = {
    create(context) {
        return {
            ImportDeclaration(node) {
                const invalidNodeSources = [];
                invalidNodeSources.push(node.source.value.startsWith('@administration/'));

                if (invalidNodeSources.includes(true)) {
                    context.report({
                        loc: node.source.loc.start,
                        message: `\
You can't use imports directly from the SnapAdmin Core via "${node.source.value}". \
Use the global SnapAdmin object directly instead (https://developer.snapadmin.net/docs/guides/plugins/plugins/administration/the-SnapAdmin-object)`,
                    });
                }
            },
        };
    },
};
